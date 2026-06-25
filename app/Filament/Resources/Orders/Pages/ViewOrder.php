<?php

namespace App\Filament\Resources\Orders\Pages;

use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\Orders\OrderResource;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;
    // protected  string $view = 'filament.pages.view-order';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('downloadInvoice')
                ->label('Download PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {

                    $pdf = Pdf::loadView('pdf.order-invoice', [
                        'order' => $this->record,
                    ]);

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'order-' . $this->record->id . '.pdf'
                    );
                }),

        ];

    }
}
