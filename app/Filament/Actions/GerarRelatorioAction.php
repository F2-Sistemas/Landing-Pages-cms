<?php

namespace App\Filament\Actions;

use App\Filament\Resources\AnswerResource;
use App\Models\Agency;
use App\Models\Answer;
use App\Models\AuditForm;
use App\Models\FormOptionList;
use App\Models\Nonconformity;
use Closure;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Actions\Action as ActionNotification;
use App\Models\Report;
use Filament\Tables\Actions\Action;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Style\Language;

class GerarRelatorioAction extends Action
{
    use CanCustomizeProcess;

    protected ?Closure $mutateRecordDataUsing = null;

    public static function getDefaultName(): ?string
    {
        return 'gerarrelatorio';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Gerar Relatório');

        // $this->modalHeading(fn (): string => __('XXXX', ['label' => $this->getRecordTitle()]));

        // $this->modalSubmitActionLabel(__('filament-actions::edit.single.modal.actions.save.label'));

        // $this->successNotificationTitle(__('filament-actions::edit.single.notifications.saved.title'));

        // $this->icon('heroicon-m-pencil-square');

        $this->fillForm(function (Model $record, Table $table): array {
            if ($translatableContentDriver = $table->makeTranslatableContentDriver()) {
                $data = $translatableContentDriver->getRecordAttributesToArray($record);
            } else {
                $data = $record->attributesToArray();
            }

            if ($this->mutateRecordDataUsing) {
                $data = $this->evaluate($this->mutateRecordDataUsing, ['data' => $data]);
            }

            return $data;
        });

        $this->action(function (): void {
            $this->process(function (array $data, Model $record, Table $table) {
                $fileName = 'Relatorio_' . $record->id . '.docx';

                if (!file_exists(storage_path('app/public/reports/' . $fileName)) || 1 == 1) {
                    $data = Report::query()->where('id', 1)->first(); //TODO: refact. Está ERRADO
                    $conteudo = $data['conteudo'];
                    $logoAgencia = $data['config']['logoAgencia'] ?? false;

                    if ($logoAgencia) {
                        $dadosAgencia = Agency::query()->where('id', 1)->first()->toArray(); //TODO: refact. Está ERRADO
                        $logo = storage_path('app/public/' . $dadosAgencia['logo']);
                        $logoTopo = storage_path('app/public/' . $dadosAgencia['logo']);
                    } else {
                        $logo = 'images/Logo_VF_cima_FClaro.png';
                        $logoTopo = 'images/Logo_VF_Lado_FClaro.png';
                    }

                    $phpWord = new \PhpOffice\PhpWord\PhpWord();

                    // ****** INÍCIO DA CONFIGURACAO GERAL
                    $phpWord->getSettings()->setThemeFontLang(new Language(Language::PT_BR));

                    $phpWord->addTitleStyle(1, ['size' => 16, 'bold' => true], ['numStyle' => 'hNum', 'numLevel' => 0]);
                    $phpWord->addTitleStyle(2, ['size' => 14, 'bold' => true], ['numStyle' => 'hNum', 'numLevel' => 1]);
                    $phpWord->addTitleStyle(3, ['size' => 12, 'bold' => true], ['numStyle' => 'hNum', 'numLevel' => 2]);

                    $phpWord->setDefaultFontName('Tahoma');
                    $phpWord->setDefaultFontSize(12);
                    $phpWord->setDefaultParagraphStyle(['spacing' => 20, 'spaceAfter' => 120, 'alignment' => Jc::BOTH]);

                    $phpWord->addNumberingStyle(
                        'hNum',
                        [
                            'type' => 'multilevel', 'levels' => [
                                ['pStyle' => 'Heading1', 'format' => 'decimal', 'text' => '%1'],
                                ['pStyle' => 'Heading2', 'format' => 'decimal', 'text' => '%1.%2'],
                                ['pStyle' => 'Heading3', 'format' => 'decimal', 'text' => '%1.%2.%3'],
                            ]
                        ]
                    );

                    $phpWord->setDefaultParagraphStyle(
                        [
                            'alignment' => Jc::BOTH,
                            'spacing' => 20,
                        ]
                    );

                    $cellHCentered = ['alignment' => Jc::CENTER];

                    // ****** FIM DA CONFIGURACAO GERAL

                    // ****** INÍCIO DA CAPA
                    $capa = $phpWord->addSection(['vAlign' => VerticalJc::CENTER]);
                    $capa->addTextBreak(4);
                    $capa->addImage($logo, ['height' => 122, 'alignment' => Jc::CENTER]);
                    $capa->addTextBreak(6);
                    $capa->addTextRun($cellHCentered)->addText($data['titulo'] ?? '', ['name' => 'Tahoma', 'align' => 'center', 'size' => 22, 'bold' => true]);
                    $capa->addTextBreak(5);

                    $texto = preg_replace("/\r\n|\r|\n/", '<br/>', $data['config']['subTitulo'] ?? '');
                    $texto = '<div style="text-align:center">' . $texto . '</div>';

                    Html::addHtml($capa, $texto);

                    $capa->addTextBreak(5);
                    $capa->addTextRun($cellHCentered)->addText($data['config']['localData'] ?? '', ['name' => 'Tahoma', 'align' => 'center', 'size' => 12, 'bold' => true]);
                    // ****** FIM DA CAPA

                    // ****** INÍCIO DO DOCUMENTO COM INDEX, HEADER E FOOTER
                    $section = $phpWord->addSection();
                    $section->getStyle()->setPageNumberingStart(2);

                    $header = $section->addHeader();
                    $table = $header->addTable();
                    $table->addRow();
                    $cell = $table->addCell(8500);
                    $textrun = $cell->addTextRun();
                    $textrun->addText($data['titulo'] ?? '', ['name' => 'Tahoma', 'size' => 15, 'bold' => true]);
                    $table->addCell(1500)->addImage($logoTopo, ['height' => 36, 'alignment' => Jc::END]);

                    $footer = $section->addFooter();
                    $footer->addPreserveText('{PAGE} de {NUMPAGES}.', null, ['alignment' => Jc::END]);
                    $lineStyle = ['weight' => 2, 'width' => 350, 'height' => 1, 'color' => 'blue', 'align' => 'center'];
                    $footer->addLine($lineStyle);

                    // $texto = preg_replace("/\r\n|\r|\n/", '<br/>', $data['config']['rodape'] ?? '');
                    // $texto = '<div style="text-align:center">' . $texto . '</div>';

                    // Html::addHtml($footer, $texto);

                    $footer->addTextRun(['alignment' => Jc::CENTER])->addText($data['config']['rodape'] ?? '');

                    // $section->addTOC([$fontStyle], [$tocStyle], [$minDepth], [$maxDepth]);
                    $fontStyleIndex = ['spaceAfter' => 60, 'size' => 12];
                    $section->addText('Índice', ['name' => 'Tahoma', 'size' => 16, 'bold' => true]);
                    $section->addTextBreak(1);
                    $section->addTOC($fontStyleIndex, null, 1, 2);
                    $section->addTextBreak(1);
                    $section->addPageBreak();
                    // ****** FIM DO DOCUMENTO COM INDEX, HEADER E FOOTER

                    // ****** INÍCIO DO DOCUMENTO
                    foreach ($conteudo as $value) {
                        if ($value['type'] == 'blocoTexto') {
                            inserirBlocoTexto($section, $value['data']);
                        }

                        if ($value['type'] == 'blocoForms') {
                            inserirBlocoForms($section, $value['data']);
                        }
                    }
                    // ****** FIM DO DOCUMENTO

                    // ****** INÍCIO DA GERACAO DO ARQUIVO
                    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
                    $objWriter->save(storage_path('app/public/reports/' . $fileName));

                    // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
                    // $objWriter->save('helloWorld.html');
                    // ****** FIM DA GERACAO DO ARQUIVO
                }

                Notification::make()
                    ->title('Relatório gerado com sucesso')
                    ->success()
                    ->body('Clique aqui para salvar o relatório')
                    ->icon('heroicon-o-document-text')
                    ->duration(10000)
                    ->actions([
                        ActionNotification::make('view')
                            ->label('Salvar arquivo')
                            ->button()
                            ->url(url('storage/reports/' . $fileName), shouldOpenInNewTab: false)
                    ])
                    ->sendToDatabase(auth()->user())
                    ->send();
            });

            $this->success();
        });
    }

    public function mutateRecordDataUsing(?Closure $callback): static
    {
        $this->mutateRecordDataUsing = $callback;

        return $this;
    }
}

function inserirBlocoTexto($section, $data)
{
    $section->addTitle($data['blocoTextoTitulo'], 1);
    $section->addTextBreak(1);
    $texto = str_replace('<br>', '<br/>', $data['blocoTextoConteudo']);

    // $doc = new DOMDocument();
    // $doc->loadHTML($texto);
    // $doc->saveHTML();
    // \PhpOffice\PhpWord\Shared\Html::addHtml($section, $doc->saveHTML(),true);

    Html::addHtml($section, $texto);
    $section->addPageBreak();
}

function arrumarOpcoes($opcoes)
{
    foreach ($opcoes as $key => $value) {
        $dados[$value['id']]['form_option_id'] = $value['form_option_id'];
        $dados[$value['id']]['opcao'] = $value['opcao'];
        $dados[$value['id']]['conforme'] = $value['conforme'];
    }

    return $dados;
}

function inserirBlocoForms($section, $data)
{
    $section->addTitle($data['blocoFormsTitulo'], 1);
    $section->addTextBreak(1);

    $opcoes = FormOptionList::query()->get()->toArray();
    $opcoes = arrumarOpcoes($opcoes);

    $formulario = AuditForm::query()->where('id', $data['blocoFormsForm'])->first();
    $formulario = $formulario['conteudo'];

    foreach ($data['blocoFormsPlans'] as $key => $valueForm) {
        $answers = Answer::query()->where('audit_form_list_id', $valueForm)->get();
        $answers = AnswerResource::formatAnswers($answers);

        $formatTable = ['cellMargin' => 0, 'cellMarginRight' => 0, 'cellMarginBottom' => 0, 'cellMarginLeft' => 0];
        $formatCell = ['valign' => 'center', 'borderSize' => 1, 'borderColor' => '5f5f5f'];
        $formatCell2rols = ['gridSpan' => 2, 'valign' => 'center', 'borderSize' => 1, 'borderColor' => '5f5f5f'];
        $formatCell3rols = ['gridSpan' => 3, 'valign' => 'center', 'borderSize' => 1, 'borderColor' => '5f5f5f'];
        $formatCell4rols = ['gridSpan' => 4, 'valign' => 'center', 'borderSize' => 1, 'borderColor' => '5f5f5f'];
        $formatCellTitle = ['valign' => 'center', 'borderSize' => 1, 'bgColor' => 'd3d3d3', 'borderColor' => '5f5f5f'];
        $formatCellTitle3 = ['gridSpan' => 3, 'valign' => 'center', 'borderSize' => 1, 'bgColor' => 'd3d3d3', 'borderColor' => '5f5f5f'];
        $cellHCentered = ['alignment' => Jc::CENTER];
        $withCell1 = 3500;
        $withCell2 = 1000;
        $withCell3 = 4500;
        $withCellNC1 = 2300;
        $withCellNC2 = 2100;
        $withCellNC3 = 3300;
        $withCellNC4 = 1500;

        $section->addTitle('Nome do Planejamento da aplicacao - ' . $valueForm, 2);
        $section->addTextBreak(1);

        $contentTable = $section->addTable($formatTable);
        $contentTable->addRow(1000, ['exactHeight' => true]);
        $cell1 = $contentTable->addCell($withCell1, $formatCellTitle3);
        $cell1->addTextRun($cellHCentered)->addText("Respostas do Formulário");

        foreach ($formulario as $value) {
            $answer = $answers[$value['data']['uuid']]['resposta'] ?? [];

            if ($value['type'] == 'multiplaEscolha') {
                // Buscar a lista de opcoes de resposta para preencher
                $comentario = $answers[$value['data']['uuid']]['comentario'] ?? '';
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell);
                $cell1->addText($value['data']['pergunta'], ['bold' => true]);

                if ($value['data']['MErespostaUnica']) {
                    $cell2 = $contentTable->addCell($withCell2, $formatCell);
                } else {
                    $cell2 = $contentTable->addCell($withCell2, $formatCell2rols);
                }

                if (is_array($answer)) {
                    foreach ($answer as $valueAnswer) {
                        $cell2->addText($opcoes[$valueAnswer]['opcao']);
                    }
                } else {
                    $cell2->addText($opcoes[$answer]['opcao']);
                }

                if ($value['data']['MErespostaUnica']) {
                    // Inserir os comentários
                    $cell3 = $contentTable->addCell($withCell3, $formatCell);
                    $cell3->addText($comentario);

                    // Inserir os comentários
                    $images = $answers[$value['data']['uuid']]['images'] ?? [];

                    if (!empty($images)) {
                        foreach ($images as $valueImage) {
                            $cell3->addImage(storage_path('app/public/' . $valueImage['imagem']), ['width' => 350, 'alignment' => Jc::CENTER]);
                            $cell3->addText($valueImage['label']);
                        }
                    }
                }
            }

            if ($value['type'] == 'multiplaEscolhaOpcoes') {
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell3rols);
                $cell1->addText($value['data']['pergunta'], ['bold' => true]);

                if (is_array($answer)) {
                    foreach ($answer as $valueAnswer) {
                        $cell1->addText($value['data']['opcoes'][$valueAnswer]);
                    }
                } else {
                    // $opcao = $value['data']['opcoes'][$answer] ?? '';
                    $cell1->addText($value['data']['opcoes'][$answer] ?? '');
                }
                // $cell1->addText($opcao);
            }

            if ($value['type'] == 'respostaCurta') {
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell3rols);
                $cell1->addText($value['data']['pergunta'], ['bold' => true]);
                $cell1->addText($answer);
            }

            if ($value['type'] == 'respostaParagrafo') {
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell3rols);
                $cell1->addText($value['data']['pergunta'], ['bold' => true]);
                $cell1->addText($answer);
            }

            if (in_array($value['type'], ['data', 'dataHora'])) {
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell3rols);
                $cell1->addText($value['data']['pergunta'], ['bold' => true]);
                $cell1->addText($answer);
            }

            if ($value['type'] == 'arquivo') {
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell3rols);
                $cell1->addText($value['data']['pergunta'], ['bold' => true]);

                if (!empty($answer)) {
                    foreach ($answer as $valueAnswer) {
                        $cell1->addText($valueAnswer['arquivo']);

                        if ($value['data']['solicitar_label'] == 'individual') {
                            $cell1->addTextRun($cellHCentered)->addText($valueAnswer['label'] ?? '');
                        }
                        $cell1->addTextBreak(1);
                    }

                    if ($value['data']['solicitar_label'] == 'geral') {
                        $cell1->addTextRun($cellHCentered)->addText($answer[0]['label'] ?? '');
                        $cell1->addTextBreak(1);
                    }
                }
            }

            if ($value['type'] == 'foto') {
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell3rols);
                $cell1->addText($value['data']['pergunta'], ['bold' => true]);

                if (!empty($answer)) {
                    foreach ($answer as $valueAnswer) {
                        if (file_exists(storage_path('app/public/' . $valueAnswer['arquivo']))) {
                            $cell1->addImage(storage_path('app/public/' . $valueAnswer['arquivo']), ['width' => 350, 'alignment' => Jc::CENTER]);

                            if ($value['data']['solicitar_label'] == 'individual') {
                                $cell1->addTextRun($cellHCentered)->addText($valueAnswer['label'] ?? '');
                            }
                        }
                        $cell1->addTextBreak(1);
                    }

                    if ($value['data']['solicitar_label'] == 'geral') {
                        $cell1->addTextRun($cellHCentered)->addText($answer[0]['label'] ?? '');
                        $cell1->addTextBreak(1);
                    }
                }
            }
        }

        if ($data['insertNonConformities']) {
            // Inserir tabela com as não conformidades
            $section->addTextBreak(1);
            $section->addTitle('Não conformidades identificadas', 3);
            $section->addTextBreak(1);

            $contentTable = $section->addTable($formatTable);
            $contentTable->addRow(1000, ['exactHeight' => true]);
            $cell1 = $contentTable->addCell($withCellNC1, $formatCellTitle);
            $cell1->addTextRun($cellHCentered)->addText("Pergunta");
            $cell2 = $contentTable->addCell($withCellNC2, $formatCellTitle);
            $cell2->addTextRun($cellHCentered)->addText("Referência");
            $cell3 = $contentTable->addCell($withCellNC3, $formatCellTitle);
            $cell3->addTextRun($cellHCentered)->addText("Recomendação");
            $cell4 = $contentTable->addCell($withCellNC4, $formatCellTitle);
            $cell4->addTextRun($cellHCentered)->addText("Prazo");

            $nonConformities = Nonconformity::query()->where('audit_form_list_id', $valueForm)->get()->toArray();

            foreach ($nonConformities as $valueNC) {
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCellNC1, $formatCell);
                $cell1->addText($valueNC['pergunta']);
                $cell2 = $contentTable->addCell($withCellNC2, $formatCell);
                $cell2->addText($valueNC['referencia']);
                $cell3 = $contentTable->addCell($withCellNC3, $formatCell);
                $cell3->addText($valueNC['recomendacao']);
                $cell4 = $contentTable->addCell($withCellNC4, $formatCell);
                $cell4->addText($valueNC['prazo']);
            }
        }

        $section->addTextBreak(1);
    }

    $section->addPageBreak();
}
