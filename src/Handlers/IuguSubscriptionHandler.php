<?php

namespace Edbizarro\Slacker\Iugu\Handlers;

use Carbon\Carbon;
use Edbizarro\Slacker\Iugu\Services\IuguService;
use Illuminate\Support\Collection;
use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;
use Spatie\SlashCommand\Handlers\SignatureHandler;

/**
 * Class IuguSubscriptionHandler.
 */
class IuguSubscriptionHandler extends SignatureHandler
{
    protected $signature = '* iugu:subscription';

    protected $description = 'Get subscription information.';

    /**
     * @var IuguService
     */
    protected $iuguService;

    protected $responseFields = [
        'name' => 'nome',
        'active' => 'ativa',
        'in_trial' => 'trial',
        'plan_name' => 'plano assinado',
        'price_cents' => 'valor da assinatura',
        'expires_at' => 'data de expiração',
    ];

    /**
     * IuguCustomerHandler constructor.
     *
     * @param Request $request
     * @param IuguService $iuguService
     */
    public function __construct(Request $request)
    {
        $this->iuguService = app()->make(IuguService::class);

        parent::__construct($request);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $customerEmail = $this->getArgument('customerEmail');

        $customerData = $this->getCustomerData($customerEmail);

        if ($customerData->count() === 0) {
            return Response::create($request)
                ->withAttachment(
                    Attachment::create()
                        ->setColor(
                            Attachment::COLOR_WARNING
                        )
                        ->setText('No results found.')
                );
        }

        $slackResponse = Response::create($request);

        $slackAttachment = Attachment::create()
            ->setColor(Attachment::COLOR_GOOD)
            ->setPreText('Iugu customer info.');

        $slackAttachment->addFields($customerData->all());

        return $slackResponse->withAttachment($slackAttachment);
    }

    /**
     * @param $customerEmail
     * @return Collection
     */
    protected function getCustomerData($customerEmail): Collection
    {
        $customer = $this->iuguService->customer()->find(['limit' => 1, 'query' => $customerEmail]);

        if ($customer->count() === 0) {
            return collect();
        }

        return collect($customer->map(function ($customerProps) use ($customer) {
            if ($customer->count() === 1) {
                $customerProps = $customer->first();
            }

            $invoices = $this->iuguService->subscription()->find(['customer_id' => $customerProps['id']]);

            return $this->formatCustomerData($customerProps, $invoices);
        })->first());
    }

    /**
     * @param array $customerProps
     * @param Collection $invoices
     * @return Collection
     */
    protected function formatCustomerData(array $customerProps, Collection $invoices): Collection
    {
        $customerData = [];

        $customerData = $invoices->reduce(function ($customerData, $invoice) use ($customerProps) {
            $customerData[$invoice['id']]['nome'] = $customerProps['name'];
            $customerData[$invoice['id']]['cnpj'] = $customerProps['cpf_cnpj'];

            $customerData[$invoice['id']]['Ativa'] = ($invoice['active'] == 1) ? 'sim' : 'não';
            $customerData[$invoice['id']]['Trial'] = ($invoice['in_trial'] === null) ? 'não' : 'sim';
            $customerData[$invoice['id']]['Expira em'] = (new Carbon($invoice['expires_at']))->format('d/m/Y');
            $customerData[$invoice['id']]['Assinatura'] = $invoice['plan_name'];
            $customerData[$invoice['id']]['Valor da assinatura'] = number_format($invoice['price_cents'] / 100, 2, ',', '.');

            return $customerData;
        }, $customerData);

        $customerData = collect(
                collect($customerData)->first()
            )->transform(function ($item) {
                if ($item == null) {
                    return $item = '';
                }

                return $item;
            });

        return $customerData;
    }
}
