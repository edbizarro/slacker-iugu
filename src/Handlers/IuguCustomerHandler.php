<?php

namespace Edbizarro\Slacker\Iugu\Handlers;

use Edbizarro\Slacker\Iugu\Services\IuguService;
use Illuminate\Support\Collection;
use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;
use Spatie\SlashCommand\Handlers\SignatureHandler;

/**
 * Class IuguCustomerHandler.
 */
class IuguCustomerHandler extends SignatureHandler
{
    protected $signature = '* iugu:customer:info {customerEmail}';

    protected $description = 'Get customer information.';

    /**
     * @var IuguService
     */
    protected $iuguService;

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

        if (count($customerData) == 0) {
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
            ->setTitle($customerData['name'])
            ->setPreText('Iugu customer info.');

        $slackAttachment->addFields($customerData['invoices']->all());

        return $slackResponse->withAttachment($slackAttachment);
    }

    /**
     * @param $customerEmail
     * @return Collection
     */
    protected function getCustomerData($customerEmail)
    {
        $response = $this->iuguService->customer()->find(['limit' => 1, 'query' => $customerEmail]);

        $customer = $response;

        if ($customer->count() === 0) {
            return collect();
        }

        return $customer->map(function ($customerProps) use ($customer) {
            if ($customer->count() === 1) {
                $customerProps = $customer->first();
            }

            $invoices = $this->iuguService->subscription()->find(['customer_id' => $customerProps['id']]);

            return $this->formatCustomerData($customerProps, $invoices);
        })->first();
    }

    /**
     * @param array $customerProps
     * @param Collection $invoices
     * @return array
     */
    protected function formatCustomerData(array $customerProps, Collection $invoices)
    {
        $customerData = [];
        $customerData['name'] = $customerProps['name'];
        $customerData['cnpj'] = $customerProps['cpf_cnpj'];

        $customerData = $invoices->reduce(function ($customerData, $invoice) {
            $customerData['invoices'][$invoice['id']]['active'] = $invoice['active'];
            $customerData['invoices'][$invoice['id']]['in_trial'] = $invoice['in_trial'];
            $customerData['invoices'][$invoice['id']]['expires_at'] = $invoice['expires_at'];
            $customerData['invoices'][$invoice['id']]['plan'] = $invoice['plan_name'];
            $customerData['invoices'][$invoice['id']]['price'] = number_format($invoice['price_cents'] / 100, 2, ',', '.');

            return $customerData;
        }, $customerData);

        $customerData['invoices'] = collect(
                collect($customerData['invoices'])->first()
            )->transform(function ($item) {
                if ($item == null) {
                    return $item = '';
                }

                return $item;
            });

        return $customerData;
    }
}
