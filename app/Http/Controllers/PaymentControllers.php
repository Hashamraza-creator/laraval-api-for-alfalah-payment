<?php
namespace App\Http\Controllers;
use App\Services\AlfalahPaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected AlfalahPaymentService $paymentService;

    public function __construct(AlfalahPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function pay(Request $request)
    {
        $response = $this->paymentService->initiatePayment(5000, "ORDER_".time());
        return isset($response['paymentUrl']) ? redirect($response['paymentUrl']) : back()->with('error', 'Payement failed');
    }

    public function callback(Request $request)
    {
        $orderId = $request->input('order_id');
        $status = $this->paymentService->verifyPayment($orderId);
        return $status['status'] == 'success' ? 'Payment successful' : 'Payment failed';
    }

    public function initiatePayment(Request $request, AlfalahPaymentService $alfalahPaymentService)
    {
        $orderId = $request->input('order_id');
        $amount = $request->input('amount');
        $response = $alfalahPaymentService->initiatePayment($amount, $orderId);
        return response()->json($response);
    }
    public function verifyPayment(Request $request, AlfalahPaymentService $alfalahPaymentService)
    {
        $orderId = $request->input('order_id');
        $response = $alfalahPaymentService->verifyPayment($orderId);
        return response()->json($response);
    }
}
hellox
