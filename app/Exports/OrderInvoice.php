<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Order;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class OrderInvoice implements FromView, WithEvents, ShouldAutoSize
{
    use Exportable;

    public function __construct($invoice){
        $this->invoice = $invoice;
    }

    public function registerEvents(): array
    {
        // TODO: Implement registerEvents() method.
        //manipulasi Cell
        return [
            AfterSheet::class => function(AfterSheet $event){
                //Cell terkait akan di-merge
                $event->sheet->mergeCells('A1:C1');
                $event->sheet->mergeCells('A2:B2');
                $event->sheet->mergeCells('A3:C3');

                //style untuk cell
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => [
                            'argb' => 'FFA0A0A0',
                        ],
                        'endColor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ];

                //terapkan style tersebut
                $event->sheet->getStyle('A9:E9')->applyFromArray($styleArray);

                //formatting style untuk cell terkait
                $headCustomer = [
                    'font' => [
                        'bold' => true,
                    ]
                ];
                $event->sheet->getStyle('A5:A7')->applyFromArray($headCustomer);
            }
        ];
    }

    public function view(): View
    {
        //mengambil data transaksi berdasarkan invoice yang diterima
        //dari controller
        $order = Order::where('invoice', $this->invoice)
            ->with('customer', 'order_detail', 'order_detail.product')->first();

        //kirim data ke file invoice excel
        return view('orders.report.invoice_excel', [
            'order' => $order
        ]);
    }
}

?>
