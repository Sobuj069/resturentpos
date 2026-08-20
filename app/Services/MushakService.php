<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Order;

class MushakService
{
    /**
     * Generate NBR Mushak 6.3 formatted tax invoice payload
     */
    public function formatMushak63Invoice(Order $order): array
    {
        $branch = $order->branch ?? Branch::first();

        // Calculate tax breakdown
        $items = [];
        $sl = 1;
        $totalBaseAmount = 0;
        $totalSdAmount = 0;
        $totalVatAmount = 0;

        foreach ($order->items as $item) {
            $basePrice = $item->subtotal;
            $vatAmt = $item->vat_amount;
            $sdAmt = 0; // Supplementary duty if applicable

            $totalBaseAmount += $basePrice;
            $totalVatAmount += $vatAmt;

            $items[] = [
                'sl' => $sl++,
                'name' => $item->item_name . ($item->variant_name ? " ({$item->variant_name})" : ''),
                'unit' => 'pcs',
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $basePrice,
                'sd_rate' => '0%',
                'sd_amount' => 0.00,
                'vat_rate' => number_format($order->vat_percent, 1) . '%',
                'vat_amount' => $vatAmt,
                'grand_total' => $basePrice + $vatAmt,
            ];
        }

        // NBR Standard QR Data format
        $qrString = sprintf(
            "BIN:%s|CHALAN:%s|TOTAL:%.2f|VAT:%.2f|DATE:%s",
            $branch->bin_number ?? '001234567-0101',
            $order->mushak_number ?? $order->order_number,
            $order->grand_total,
            $order->vat_amount,
            $order->created_at->format('Y-m-d H:i:s')
        );

        return [
            'gov_header' => 'গণপ্রজাতন্ত্রী বাংলাদেশ সরকার, জাতীয় রাজস্ব বোর্ড',
            'form_title' => 'কর চালানপত্র (মূসক-৬.৩)',
            'rule_ref' => '[বিধি ৪০ এর উপ-বিধি (১) এর দফা (গ) ও দফা (চ) দ্রষ্টব্য]',
            'branch' => [
                'name' => $branch->name ?? 'Grand Restaurant POS',
                'bin' => $branch->bin_number ?? '001234567-0101',
                'address' => $branch->address ?? 'Dhaka, Bangladesh',
                'phone' => $branch->phone ?? '01700000000',
            ],
            'invoice' => [
                'mushak_no' => $order->mushak_number,
                'order_no' => $order->order_number,
                'date' => $order->created_at->format('d/m/Y'),
                'time' => $order->created_at->format('h:i A'),
                'order_type' => strtoupper($order->order_type),
                'table_name' => $order->table ? $order->table->name : null,
                'token_number' => $order->token_number,
                'cashier_name' => $order->user ? $order->user->name : 'Staff',
                'customer_name' => $order->customer_name ?? 'সম্মানিত অতিথি (Guest)',
                'customer_phone' => $order->customer_phone,
            ],
            'items' => $items,
            'summary' => [
                'subtotal' => $order->subtotal,
                'discount_amount' => $order->discount_amount,
                'sd_amount' => $order->sd_amount,
                'vat_percent' => $order->vat_percent,
                'vat_amount' => $order->vat_amount,
                'service_charge' => $order->service_charge,
                'grand_total' => $order->grand_total,
                'paid_amount' => $order->paid_amount,
                'change_amount' => $order->change_amount,
                'payment_method' => strtoupper($order->payment_method ?? 'CASH'),
            ],
            'qr_string' => $qrString,
            'footer_text' => 'আমাদের সেবা গ্রহণের জন্য ধন্যবাদ। পুনরায় আসবেন।',
            'software_credit' => 'Powered by SmartPOS Enterprise',
        ];
    }
}
