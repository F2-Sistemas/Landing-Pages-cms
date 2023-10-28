<?php

use App\Models\Report;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Style\Language;

require __DIR__.'/../vendor/autoload.php';

// $conteudo = Report::query()->where('id', 1);
// dd($conteudo);
// $conteudo = '[{"type":"blocoTexto","data":{"uuid":"a17a9b48cf8d2a1dba592462b8578d0c","blocoTextoTitulo":"Introdu\u00e7\u00e3o","blocoTextoConteudo":"<p>asdasdas<\/p><p>das<\/p><p>d<\/p><p>asd<\/p><p><br><\/p>"}},{"type":"blocoForms","data":{"uuid":"f7c76139f2ec48dabc6011c214b93fea","blocoFormsTitulo":"Form 1","blocoFormsForm":"1","blocoFormsPlans":["3","4"]}},{"type":"blocoForms","data":{"uuid":"2ab712edb1a10ecc481448507887a355","blocoFormsTitulo":"Form adicional","blocoFormsForm":"2","blocoFormsPlans":["2","5"]}}]';
$conteudo = '[{"type":"blocoTexto","data":{"uuid":"a17a9b48cf8d2a1dba592462b8578d0c","blocoTextoTitulo":"DIRETORIA COLEGIADA","blocoTextoConteudo":"<p><br><br><br><br><br><br><br>Gustavo Gast\u00e3o Corgosinho Cardoso<\/p><p><strong>Diretor Geral<\/strong><\/p><p>Murilo Pizato Marques<\/p><p><strong>Diretor Administrativo Financeiro<\/strong><\/p><p>Thays Rodrigues da Costa<\/p><p><strong>Diretora T\u00e9cnica Operacional<\/strong><\/p><p><strong>EQUIPE T\u00c9CNICA<\/strong><\/p><p>Alex Rodrigues Alves<\/p><p><strong>Coordenador de Regula\u00e7\u00e3o Econ\u00f4mica<\/strong><\/p><p>Rodrigo de Vasconcellos Viana Medeiros<\/p><p><strong>Analista de Regula\u00e7\u00e3o Econ\u00f4mica<\/strong><\/p><p>Eliziane do Amaral<\/p><p><strong>Analista de Regula\u00e7\u00e3o Econ\u00f4mica<\/strong><\/p><p>Paola Silva Ara\u00fajo<\/p><p><strong>Assistente Administrativo<\/strong><\/p><p>Tatiane Batista Damasceno<\/p><p><strong>Analista de Fiscaliza\u00e7\u00e3o<\/strong><\/p><p>Anderson da Silva Galdino<\/p><p><strong>Analista de Fiscaliza\u00e7\u00e3o<\/strong><\/p><p>Rodrigo Pena do Carmo<\/p><p><strong>Coordenador de Fiscaliza\u00e7\u00e3o<\/strong><\/p><p><strong>AG\u00caNCIA REGULADORA INTERMUNICIPAL DOS SERVI\u00c7OS DE SANEAMENTO DA ZONA DA MATA DE<\/strong><\/p><p><strong>MINAS GERAIS E ADJAC\u00caNCIAS<\/strong><\/p><p>ARIS ZM - Ag\u00eancia Reguladora Intermunicipal dos Servi\u00e7os de Saneamento da Zona da Mata de Minas Gerais e Adjac\u00eancias<br>Rua Jos\u00e9 dos Santos, 275, Vi\u00e7osa-MG - CEP: 36570 -266<br>Tel.: (31) 3891-5636<\/p><p>www.ariszm.mg.gov.br<\/p>"}},{"type":"blocoTexto","data":{"blocoTextoTitulo":"VISITA DIAGN\u00d3STICO","blocoTextoConteudo":"<p>Para realiza\u00e7\u00e3o do diagn\u00f3stico do servi\u00e7o manejo de res\u00edduos s\u00f3lidos urbanos foi realizada uma visita ao munic\u00edpio de Muria\u00e9 no dia 14 de fevereiro de 2023, na qual foram abordados, entre outros, os seguintes pontos:<\/p><ul><li>Estrutura, organiza\u00e7\u00e3o e gest\u00e3o administrativa;<\/li><li>Contratos de presta\u00e7\u00e3o de servi\u00e7o;<\/li><li>Plano de trabalho, Recursos Humanos, Programas de Capacita\u00e7\u00e3o, Medicina e<\/li><li>Seguran\u00e7a do Trabalho;<\/li><li>Coleta e Acondicionamento dos RSU;<\/li><li>Transporte e ve\u00edculos;<\/li><li>Destina\u00e7\u00e3o e\/ou disposi\u00e7\u00e3o final dos res\u00edduos.<\/li><\/ul>"}},{"type":"blocoTexto","data":{"blocoTextoTitulo":"Introdu\u00e7\u00e3o","blocoTextoConteudo":"<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; A lei federal 11.445 de 2007, alterada pela lei federal 14.026 de 2020, traz diretrizes nacionais para o saneamento b\u00e1sico e para a pol\u00edtica federal de saneamento b\u00e1sico, definindo como o conjunto de servi\u00e7os p\u00fablicos, as infraestruturas e instala\u00e7\u00f5es operacionais de abastecimento de \u00e1gua pot\u00e1vel, esgotamento sanit\u00e1rio, limpeza urbana e manejo dos res\u00edduos s\u00f3lidos e drenagem e manejo das \u00e1guas pluviais urbanas, o saneamento b\u00e1sico.<\/p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;A referida lei traz fundamentos para a presta\u00e7\u00e3o dos servi\u00e7os de saneamento b\u00e1sico, dentre eles, a universaliza\u00e7\u00e3o do acesso e efetiva presta\u00e7\u00e3o dos servi\u00e7os, a integralidade, a efici\u00eancia e a sustentabilidade econ\u00f4mica, seguran\u00e7a, qualidade, regularidade, continuidade e adequa\u00e7\u00e3o \u00e0 sa\u00fade p\u00fablica, \u00e0 conserva\u00e7\u00e3o dos recursos naturais e \u00e0 prote\u00e7\u00e3o do meio ambiente.<\/p><p>No cap\u00edtulo II, a Lei federal 11.445\/2007, disp\u00f5e sobre o exerc\u00edcio da titularidade dos servi\u00e7os. No art.8o, \u00a75o, a referida lei estabelece que: \u201cO titular dos servi\u00e7os p\u00fablicos de saneamento b\u00e1sico dever\u00e1 definir a entidade respons\u00e1vel pela regula\u00e7\u00e3o e fiscaliza\u00e7\u00e3o desses servi\u00e7os, independentemente da modalidade de sua presta\u00e7\u00e3o\u201d.<\/p><p>De acordo com a legisla\u00e7\u00e3o vigente, a fun\u00e7\u00e3o de regula\u00e7\u00e3o, dever\u00e1 ser desempenhada por entidade de natureza aut\u00e1rquica dotada de independ\u00eancia decis\u00f3ria e autonomia administrativa, or\u00e7ament\u00e1ria e financeira, a qual deve atender aos princ\u00edpios de transpar\u00eancia, tecnicidade, celeridade e objetividade das decis\u00f5es.<\/p><p>A lei federal 14.026 de 2020, a qual atualizou o marco legal do saneamento b\u00e1sico, atribuiu \u00e0 Ag\u00eancia Nacional de \u00c1guas e Saneamento B\u00e1sico (ANA) a compet\u00eancia para instituir normas de refer\u00eancia para a regula\u00e7\u00e3o dos servi\u00e7os p\u00fablicos de saneamento b\u00e1sico,<\/p><p>A entidade reguladora dever\u00e1 observar as normas de refer\u00eancia da ANA, para editar normas relativas \u00e0s dimens\u00f5es t\u00e9cnica, econ\u00f4mica e social de presta\u00e7\u00e3o dos servi\u00e7os p\u00fablicos de saneamento b\u00e1sico.<\/p><p>A lei do saneamento, em seu art. 22, disp\u00f5e sobre os objetivos da regula\u00e7\u00e3o dos servi\u00e7os de saneamento b\u00e1sico, sendo eles: o estabelecimento de padr\u00f5es e normas para a adequada presta\u00e7\u00e3o dos servi\u00e7os e para a satisfa\u00e7\u00e3o dos usu\u00e1rios; a garantia do cumprimento das condi\u00e7\u00f5es e metas estabelecidas nos contratos de presta\u00e7\u00e3o de servi\u00e7os e nos planos municipais ou de presta\u00e7\u00e3o regionalizadas de saneamento b\u00e1sico; a preven\u00e7\u00e3o e a repress\u00e3o do abuso do<\/p><p>ARIS ZM \u2013 Autarquia Intermunicipal - CNPJ: 44.781.803\/0001-04 Rua Jos\u00e9 dos Santos, 275, Vi\u00e7osa-MG \u2013 (31) 3891-5636<\/p><p><strong>AG\u00caNCIA REGULADORA INTERMUNICIPAL DOS SERVI\u00c7OS DE SANEAMENTO DA ZONA DA MATA DE MINAS GERAIS E ADJAC\u00caNCIAS<\/strong><\/p><p>poder econ\u00f4mico, ressalvada a compet\u00eancia dos \u00f3rg\u00e3os integrantes do Sistema Brasileiro de Defesa da Concorr\u00eancia; e a defini\u00e7\u00e3o de tarifas que assegurem tanto o equil\u00edbrio econ\u00f4mico- financeiro dos contratos como a modicidade tarif\u00e1ria, por meio de mecanismos que gerem efici\u00eancia e efic\u00e1cia dos servi\u00e7os e que permitam o compartilhamento dos ganhos de produtividade com os usu\u00e1rios.<\/p><p>No munic\u00edpio de Muria\u00e9 a Ag\u00eancia Reguladora Intermunicipal dos Servi\u00e7os de Saneamento da Zona da Mata de Minas Gerais e Adjac\u00eancias \u2013 ARIS ZM foi \u00e0 institui\u00e7\u00e3o definida para exercer as atividades de regula\u00e7\u00e3o e fiscaliza\u00e7\u00e3o dos servi\u00e7os de saneamento b\u00e1sico, atrav\u00e9s da Lei Municipal no 6.502\/2022, mediante termo de conv\u00eanio.<\/p><p>O Conv\u00eanio de Regula\u00e7\u00e3o no 036\/2022, foi firmado em novembro de 2022 entre o Munic\u00edpio de Muria\u00e9 e a ARIS ZM. O objetivo do referido documento foi o estabelecimento de obriga\u00e7\u00f5es entre a concedente e o convenente para que este exer\u00e7a, em proveito e em nome da concedente, e conforme a colabora\u00e7\u00e3o e diretrizes definidas por este, as atividades de regula\u00e7\u00e3o e fiscaliza\u00e7\u00e3o, inclusive com poder de pol\u00edcia, dos servi\u00e7os de saneamento de manejo dos res\u00edduos s\u00f3lidos e limpeza urbana prestados no Munic\u00edpio.<\/p><p>O presente relat\u00f3rio apresenta o diagn\u00f3stico do manejo de res\u00edduos s\u00f3lidos no munic\u00edpio, o qual buscou conhecer e avaliar a organiza\u00e7\u00e3o, m\u00e9todos e processos, recursos humanos e materiais empregados pela \u00e1rea t\u00e9cnica do prestador, al\u00e9m dos elementos t\u00e9cnicos da infraestrutura do manejo de res\u00edduos s\u00f3lidos urbanos do munic\u00edpio, identificando fatores que est\u00e3o prejudicando ou que possam vir a prejudicar a qualidade dos servi\u00e7os e do atendimento aos usu\u00e1rios.<\/p>"}},{"type":"blocoForms","data":{"uuid":"f7c76139f2ec48dabc6011c214b93fea","blocoFormsTitulo":"Form 1","blocoFormsForm":"1","blocoFormsPlans":["3","4"]}},{"type":"blocoForms","data":{"uuid":"2ab712edb1a10ecc481448507887a355","blocoFormsTitulo":"Form adicional","blocoFormsForm":"2","blocoFormsPlans":["2","5"]}}]';
// $conteudo = '[{"type":"blocoTexto","data":{"uuid":"a17a9b48cf8d2a1dba592462b8578d0c","blocoTextoTitulo":"DIRETORIA COLEGIADA","blocoTextoConteudo":"<p>Gustavo Gast\u00e3o Corgosinho Cardoso<\/p><p><strong>Diretor Geral<\/strong><\/p><p>Murilo Pizato Marques<\/p><p><strong>Diretor Administrativo Financeiro<\/strong><\/p><p>Thays Rodrigues da Costa<\/p><p><strong>Diretora T\u00e9cnica Operacional<\/strong><\/p><p><figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image\/png&quot;,&quot;filename&quot;:&quot;Captura de Tela 2023-09-16 \u00e0s 16.14.00.png&quot;,&quot;filesize&quot;:111823,&quot;height&quot;:312,&quot;href&quot;:&quot;http:\/\/127.0.0.1:8000\/storage\/FuOOceBnIHMjFTkwrCzSktFoggtAKnql7eyOzP7x.png&quot;,&quot;url&quot;:&quot;http:\/\/127.0.0.1:8000\/storage\/FuOOceBnIHMjFTkwrCzSktFoggtAKnql7eyOzP7x.png&quot;,&quot;width&quot;:686}\" data-trix-content-type=\"image\/png\" data-trix-attributes=\"{&quot;presentation&quot;:&quot;gallery&quot;}\" class=\"attachment attachment--preview attachment--png\"><a href=\"http:\/\/127.0.0.1:8000\/storage\/FuOOceBnIHMjFTkwrCzSktFoggtAKnql7eyOzP7x.png\"><img src=\"http:\/\/127.0.0.1:8000\/storage\/FuOOceBnIHMjFTkwrCzSktFoggtAKnql7eyOzP7x.png\" width=\"686\" height=\"312\"><figcaption class=\"attachment__caption\"><span class=\"attachment__name\">Captura de Tela 2023-09-16 \u00e0s 16.14.00.png<\/span> <span class=\"attachment__size\">109.2 KB<\/span><\/figcaption><\/a><\/figure><strong>EQUIPE T\u00c9CNICA<\/strong><\/p><p>Alex Rodrigues Alves<\/p><p><strong>Coordenador de Regula\u00e7\u00e3o Econ\u00f4mica<\/strong><\/p><p>Rodrigo de Vasconcellos Viana Medeiros<\/p><p><strong>Analista de Regula\u00e7\u00e3o Econ\u00f4mica<\/strong><\/p><p>Eliziane do Amaral<\/p><p><strong>Analista de Regula\u00e7\u00e3o Econ\u00f4mica<\/strong><\/p><p>Paola Silva Ara\u00fajo<\/p><p><strong>Assistente Administrativo<\/strong><\/p><p>Tatiane Batista Damasceno<\/p><p><strong>Analista de Fiscaliza\u00e7\u00e3o<\/strong><\/p><p>Anderson da Silva Galdino<\/p><p><strong>Analista de Fiscaliza\u00e7\u00e3o<\/strong><\/p><p>Rodrigo Pena do Carmo<\/p><p><strong>Coordenador de Fiscaliza\u00e7\u00e3o<\/strong><\/p><p><strong>AG\u00caNCIA REGULADORA INTERMUNICIPAL DOS SERVI\u00c7OS DE SANEAMENTO DA ZONA DA MATA DE<\/strong><\/p><p><strong>MINAS GERAIS E ADJAC\u00caNCIAS<\/strong><\/p><p>ARIS ZM - Ag\u00eancia Reguladora Intermunicipal dos Servi\u00e7os de Saneamento da Zona da Mata de Minas Gerais e Adjac\u00eancias<br>Rua Jos\u00e9 dos Santos, 275, Vi\u00e7osa-MG - CEP: 36570 -266<br>Tel.: (31) 3891-5636<\/p><p>www.ariszm.mg.gov.br<\/p>"}},{"type":"blocoTexto","data":{"blocoTextoTitulo":"VISITA DIAGN\u00d3STICO","blocoTextoConteudo":"<p>Para realiza\u00e7\u00e3o do diagn\u00f3stico do servi\u00e7o manejo de res\u00edduos s\u00f3lidos urbanos foi realizada uma visita ao munic\u00edpio de Muria\u00e9 no dia 14 de fevereiro de 2023, na qual foram abordados, entre outros, os seguintes pontos:<\/p><ul><li>Estrutura, organiza\u00e7\u00e3o e gest\u00e3o administrativa;<\/li><li>Contratos de presta\u00e7\u00e3o de servi\u00e7o;<\/li><li>Plano de trabalho, Recursos Humanos, Programas de Capacita\u00e7\u00e3o, Medicina e<\/li><li>Seguran\u00e7a do Trabalho;<\/li><li>Coleta e Acondicionamento dos RSU;<\/li><li>Transporte e ve\u00edculos;<\/li><li>Destina\u00e7\u00e3o e\/ou disposi\u00e7\u00e3o final dos res\u00edduos.<\/li><\/ul>"}},{"type":"blocoTexto","data":{"blocoTextoTitulo":"Introdu\u00e7\u00e3o","blocoTextoConteudo":"<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; A lei federal 11.445 de 2007, alterada pela lei federal 14.026 de 2020, traz diretrizes nacionais para o saneamento b\u00e1sico e para a pol\u00edtica federal de saneamento b\u00e1sico, definindo como o conjunto de servi\u00e7os p\u00fablicos, as infraestruturas e instala\u00e7\u00f5es operacionais de abastecimento de \u00e1gua pot\u00e1vel, esgotamento sanit\u00e1rio, limpeza urbana e manejo dos res\u00edduos s\u00f3lidos e drenagem e manejo das \u00e1guas pluviais urbanas, o saneamento b\u00e1sico.<\/p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;A referida lei traz fundamentos para a presta\u00e7\u00e3o dos servi\u00e7os de saneamento b\u00e1sico, dentre eles, a universaliza\u00e7\u00e3o do acesso e efetiva presta\u00e7\u00e3o dos servi\u00e7os, a integralidade, a efici\u00eancia e a sustentabilidade econ\u00f4mica, seguran\u00e7a, qualidade, regularidade, continuidade e adequa\u00e7\u00e3o \u00e0 sa\u00fade p\u00fablica, \u00e0 conserva\u00e7\u00e3o dos recursos naturais e \u00e0 prote\u00e7\u00e3o do meio ambiente.<\/p><p>No cap\u00edtulo II, a Lei federal 11.445\/2007, disp\u00f5e sobre o exerc\u00edcio da titularidade dos servi\u00e7os. No art.8o, \u00a75o, a referida lei estabelece que: \u201cO titular dos servi\u00e7os p\u00fablicos de saneamento b\u00e1sico dever\u00e1 definir a entidade respons\u00e1vel pela regula\u00e7\u00e3o e fiscaliza\u00e7\u00e3o desses servi\u00e7os, independentemente da modalidade de sua presta\u00e7\u00e3o\u201d.<\/p><p>De acordo com a legisla\u00e7\u00e3o vigente, a fun\u00e7\u00e3o de regula\u00e7\u00e3o, dever\u00e1 ser desempenhada por entidade de natureza aut\u00e1rquica dotada de independ\u00eancia decis\u00f3ria e autonomia administrativa, or\u00e7ament\u00e1ria e financeira, a qual deve atender aos princ\u00edpios de transpar\u00eancia, tecnicidade, celeridade e objetividade das decis\u00f5es.<\/p><p>A lei federal 14.026 de 2020, a qual atualizou o marco legal do saneamento b\u00e1sico, atribuiu \u00e0 Ag\u00eancia Nacional de \u00c1guas e Saneamento B\u00e1sico (ANA) a compet\u00eancia para instituir normas de refer\u00eancia para a regula\u00e7\u00e3o dos servi\u00e7os p\u00fablicos de saneamento b\u00e1sico,<\/p><p>A entidade reguladora dever\u00e1 observar as normas de refer\u00eancia da ANA, para editar normas relativas \u00e0s dimens\u00f5es t\u00e9cnica, econ\u00f4mica e social de presta\u00e7\u00e3o dos servi\u00e7os p\u00fablicos de saneamento b\u00e1sico.<\/p><p>A lei do saneamento, em seu art. 22, disp\u00f5e sobre os objetivos da regula\u00e7\u00e3o dos servi\u00e7os de saneamento b\u00e1sico, sendo eles: o estabelecimento de padr\u00f5es e normas para a adequada presta\u00e7\u00e3o dos servi\u00e7os e para a satisfa\u00e7\u00e3o dos usu\u00e1rios; a garantia do cumprimento das condi\u00e7\u00f5es e metas estabelecidas nos contratos de presta\u00e7\u00e3o de servi\u00e7os e nos planos municipais ou de presta\u00e7\u00e3o regionalizadas de saneamento b\u00e1sico; a preven\u00e7\u00e3o e a repress\u00e3o do abuso do<\/p><p>ARIS ZM \u2013 Autarquia Intermunicipal - CNPJ: 44.781.803\/0001-04 Rua Jos\u00e9 dos Santos, 275, Vi\u00e7osa-MG \u2013 (31) 3891-5636<\/p><p><strong>AG\u00caNCIA REGULADORA INTERMUNICIPAL DOS SERVI\u00c7OS DE SANEAMENTO DA ZONA DA MATA DE MINAS GERAIS E ADJAC\u00caNCIAS<\/strong><\/p><p>poder econ\u00f4mico, ressalvada a compet\u00eancia dos \u00f3rg\u00e3os integrantes do Sistema Brasileiro de Defesa da Concorr\u00eancia; e a defini\u00e7\u00e3o de tarifas que assegurem tanto o equil\u00edbrio econ\u00f4mico- financeiro dos contratos como a modicidade tarif\u00e1ria, por meio de mecanismos que gerem efici\u00eancia e efic\u00e1cia dos servi\u00e7os e que permitam o compartilhamento dos ganhos de produtividade com os usu\u00e1rios.<\/p><p>No munic\u00edpio de Muria\u00e9 a Ag\u00eancia Reguladora Intermunicipal dos Servi\u00e7os de Saneamento da Zona da Mata de Minas Gerais e Adjac\u00eancias \u2013 ARIS ZM foi \u00e0 institui\u00e7\u00e3o definida para exercer as atividades de regula\u00e7\u00e3o e fiscaliza\u00e7\u00e3o dos servi\u00e7os de saneamento b\u00e1sico, atrav\u00e9s da Lei Municipal no 6.502\/2022, mediante termo de conv\u00eanio.<\/p><p>O Conv\u00eanio de Regula\u00e7\u00e3o no 036\/2022, foi firmado em novembro de 2022 entre o Munic\u00edpio de Muria\u00e9 e a ARIS ZM. O objetivo do referido documento foi o estabelecimento de obriga\u00e7\u00f5es entre a concedente e o convenente para que este exer\u00e7a, em proveito e em nome da concedente, e conforme a colabora\u00e7\u00e3o e diretrizes definidas por este, as atividades de regula\u00e7\u00e3o e fiscaliza\u00e7\u00e3o, inclusive com poder de pol\u00edcia, dos servi\u00e7os de saneamento de manejo dos res\u00edduos s\u00f3lidos e limpeza urbana prestados no Munic\u00edpio.<\/p><p>O presente relat\u00f3rio apresenta o diagn\u00f3stico do manejo de res\u00edduos s\u00f3lidos no munic\u00edpio, o qual buscou conhecer e avaliar a organiza\u00e7\u00e3o, m\u00e9todos e processos, recursos humanos e materiais empregados pela \u00e1rea t\u00e9cnica do prestador, al\u00e9m dos elementos t\u00e9cnicos da infraestrutura do manejo de res\u00edduos s\u00f3lidos urbanos do munic\u00edpio, identificando fatores que est\u00e3o prejudicando ou que possam vir a prejudicar a qualidade dos servi\u00e7os e do atendimento aos usu\u00e1rios.<\/p>"}},{"type":"blocoForms","data":{"uuid":"f7c76139f2ec48dabc6011c214b93fea","blocoFormsTitulo":"Form 1","blocoFormsForm":"1","blocoFormsPlans":["3","4"]}},{"type":"blocoForms","data":{"uuid":"2ab712edb1a10ecc481448507887a355","blocoFormsTitulo":"Form adicional","blocoFormsForm":"2","blocoFormsPlans":["2","5"]}}]';
$conteudo = json_decode($conteudo, true);
// dd($conteudo);

$phpWord = new \PhpOffice\PhpWord\PhpWord();

// ****** INÍCIO DA CONFIGURACAO GERAL
    $phpWord->getSettings()->setThemeFontLang(new Language(Language::PT_BR));

    $phpWord->addTitleStyle(1, array('size' => 16), array('numStyle' => 'hNum', 'numLevel' => 0));
    $phpWord->addTitleStyle(2, array('size' => 14), array('numStyle' => 'hNum', 'numLevel' => 1));
    $phpWord->addTitleStyle(3, array('size' => 12), array('numStyle' => 'hNum', 'numLevel' => 2));

    $phpWord->setDefaultFontName('Tahoma');
    $phpWord->setDefaultFontSize(12);
    $phpWord->setDefaultParagraphStyle(['spacing' => 20, 'spaceAfter' => 120, 'alignment' => Jc::BOTH]);

    $phpWord->addNumberingStyle(
        'hNum',
        array('type' => 'multilevel', 'levels' => array(
            array('pStyle' => 'Heading1', 'format' => 'decimal', 'text' => '%1'),
            array('pStyle' => 'Heading2', 'format' => 'decimal', 'text' => '%1.%2'),
            array('pStyle' => 'Heading3', 'format' => 'decimal', 'text' => '%1.%2.%3'),
            )
        )
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
    $capa->addImage('images/Logo_VF_cima_FClaro.png', ['height' => 122, 'alignment' => Jc::CENTER]);
    $capa->addTextBreak(8);
    $capa->addTextRun($cellHCentered)->addText('Relatório de Auditoria XPTO.', array('name' => 'Tahoma', 'align' => 'center', 'size' => 22, 'bold' => true));
    $capa->addTextBreak(10);
    $capa->addTextRun($cellHCentered)->addText('Local, xx de outubro de 2023', array('name' => 'Tahoma', 'align' => 'center', 'size' => 12, 'bold' => true));
// ****** FIM DA CAPA

// ****** INÍCIO DO DOCUMENTO COM INDEX, HEADER E FOOTER
    $section = $phpWord->addSection();
    $section->getStyle()->setPageNumberingStart(2);

    $header = $section->addHeader();
    $table = $header->addTable();
    $table->addRow();
    $cell = $table->addCell(8500);
    $textrun = $cell->addTextRun();
    $textrun->addText('Relatório de Auditoria XPTO.', array('name' => 'Tahoma', 'size' => 15, 'bold' => true));
    // $textrun->addLink('https://www.vamosfiscalizar.com', 'www.vamosfiacalizar.com');
    $table->addCell(1500)->addImage('images/Logo_VF_Lado_FClaro.png', ['height' => 30, 'alignment' => Jc::END]);

    $footer = $section->addFooter();
    $footer->addPreserveText('{PAGE} de {NUMPAGES}.', null, ['alignment' => Jc::END]);
    $lineStyle = array('weight' => 2, 'width' => 350, 'height' => 1, 'color' => 'blue', 'align' => 'center');
    $footer->addLine($lineStyle);
    $footer->addTextRun(['alignment' => Jc::CENTER])->addLink('https://www.vamosfiscalizar.com', 'www.vamosfiscalizar.com', ['alignment' => Jc::CENTER]);

    // $section->addTOC([$fontStyle], [$tocStyle], [$minDepth], [$maxDepth]);
    $fontStyleIndex = ['spaceAfter' => 60, 'size' => 12];
    $section->addTitle('Índice', 1);
    $section->addTextBreak(1);
    $section->addTOC($fontStyleIndex, null, 1, 2);
    $section->addTextBreak(1);
    $section->addPageBreak();
// ****** FIM DO DOCUMENTO COM INDEX, HEADER E FOOTER

// ****** INÍCIO DO DOCUMENTO
    foreach ($conteudo as $value) {
        if ($value['type']=='blocoTexto') {
            inserirBlocoTexto($section, $value['data']);
        }
        if ($value['type']=='blocoForms') {
            inserirBlocoForms($section, $value['data']);
        }
    }

    // $section->addTitle('Título 1', 1);
    // $section->addTextBreak(1);
    // $section->addText('Descrição aqui', array('spaceAfter' => 160, 'name' => 'Tahoma', 'size' => 12));
    // $section->addTextBreak(2);
    // $section->addTitle('Título 2', 2);
    // $section->addTextBreak(1);
    // $section->addText('Descrição aqui', array('spaceAfter' => 160, 'name' => 'Tahoma', 'size' => 12));


// ****** FIM DO DOCUMENTO



// ****** INÍCIO DA GERACAO DO ARQUIVO
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save('helloWorld.docx');

    // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
    // $objWriter->save('helloWorld.html');
// ****** FIM DA GERACAO DO ARQUIVO

function inserirBlocoTexto($section, $data) {
    $section->addTitle($data['blocoTextoTitulo'], 1);
    $section->addTextBreak(1);
    $texto = str_replace('<br>','<br/>',$data['blocoTextoConteudo']);

    // $doc = new DOMDocument();
	// $doc->loadHTML($texto);
	// $doc->saveHTML();
	// \PhpOffice\PhpWord\Shared\Html::addHtml($section, $doc->saveHTML(),true);

    Html::addHtml($section, $texto);
    $section->addPageBreak();
}

function inserirBlocoForms($section, $data) {
    $section->addTitle($data['blocoFormsTitulo'], 1);
    $section->addTextBreak(1);

    foreach ($data['blocoFormsPlans'] as $key => $value) {
        // Pegar os dados do formulário
        $formularios[1] = json_decode('[{"type":"multiplaEscolha","data":{"tipo_pergunta":"1","obrigatorio":true,"uuid":"4cb1d8267a49133df2ec0cb701d0894b","pergunta":"Pergunta 1 - ME","opcoes":"2"}},{"type":"multiplaEscolhaMultipla","data":{"tipo_pergunta":"2","obrigatorio":false,"uuid":"f204ed7a3ab7bfacb72664e0206568f3","pergunta":"Pergunta 2  - ME MR","opcoes":"1"}},{"type":"multiplaEscolhaOpcoes","data":{"tipo_pergunta":"1","obrigatorio":true,"uuid":"63345968f4374645117074373fca16b1","pergunta":"Pergunta 3 - ME Op\u00e7\u00f5es","opcoes":["op\u00e7\u00e3o 1","op\u00e7\u00e3o 2","op\u00e7\u00e3o 3","op\u00e7\u00e3o 4","op\u00e7\u00e3o 5"]}},{"type":"respostaCurta","data":{"tipo_pergunta":"3","obrigatorio":false,"uuid":"5360e4150444c5fbe586652c067710e3","pergunta":"Pergunta 4 - Texto Curto"}},{"type":"respostaParagrafo","data":{"tipo_pergunta":"4","obrigatorio":true,"uuid":"90853c8db37160313248d407c5224736","pergunta":"Pergunta 5 - Paragrafo"}},{"type":"data","data":{"tipo_pergunta":"5","obrigatorio":false,"uuid":"21ec43983b5c05221382bb35529db46d","pergunta":"Pergunta 6 - Data"}},{"type":"dataHora","data":{"tipo_pergunta":"6","obrigatorio":true,"uuid":"a6f77439962225e2f8f8596144309c3e","pergunta":"Pergunta 7 - Hora"}},{"type":"foto","data":{"tipo_pergunta":"7","obrigatorio":false,"uuid":"8d7293da2d92515f9f7d1ad9f65f1832","pergunta":"Pergunta 8 - Arquivo","multiplos":true,"solicitar_label":"individual"}},{"type":"foto","data":{"tipo_pergunta":"8","obrigatorio":true,"uuid":"902565be0774dd5423f5f9a050732191","pergunta":"Pergunta 9 - Imagem","multiplos":true,"solicitar_label":"geral"}},{"type":"multiplaEscolha","data":{"tipo_pergunta":"1","obrigatorio":false,"uuid":"deaa1009eaf47983f6901681a68ae12f","pergunta":"teste segundo ME","opcoes":"4"}}]',true);
        $formularios[2] = json_decode('[{"type":"arquivo","data":{"tipo_pergunta":"8","obrigatorio":false,"uuid":"53fdd624418891fd82e190a50ac5ab9f","pergunta":"A1","multiplos":false,"solicitar_label":null}},{"type":"arquivo","data":{"tipo_pergunta":"8","obrigatorio":false,"uuid":"f4bf969752820d353ebb757631fcf42b","pergunta":"A2M","multiplos":true,"solicitar_label":null}},{"type":"arquivo","data":{"tipo_pergunta":"8","obrigatorio":false,"uuid":"c52ba8589820d89d42608096f50b5cd3","pergunta":"A3MG","multiplos":true,"solicitar_label":"geral"}},{"type":"arquivo","data":{"tipo_pergunta":"8","obrigatorio":false,"uuid":"c7a9c9f884677d98e090e43010da5b7d","pergunta":"A4MI","multiplos":true,"solicitar_label":"individual"}},{"type":"multiplaEscolha","data":{"tipo_pergunta":"1","obrigatorio":true,"uuid":"81d3e13f0cb9c1af0e60f46152ddd264","pergunta":"asdasdasd","opcoes":"3"}}]',true);

        $answers[1]['deaa1009eaf47983f6901681a68ae12f']['resposta'] = '9';
        $answers[1]['63345968f4374645117074373fca16b1']['resposta'] = '3';
        $answers[1]['8d7293da2d92515f9f7d1ad9f65f1832']['resposta'] = json_decode('[{"arquivo":"imagem_teste.png","label":"asdasdasdsd"},{"arquivo":"imagem_teste.png","label":"ssssss"}]', true);
        $answers[1]['5360e4150444c5fbe586652c067710e3']['resposta'] = 'asd';
        $answers[1]['90853c8db37160313248d407c5224736']['resposta'] = 'asd
        Asda';
        $answers[1]['a6f77439962225e2f8f8596144309c3e']['resposta'] = '2023-09-20 12:10';
        $answers[1]['4cb1d8267a49133df2ec0cb701d0894b']['resposta'] = '5';
        $answers[1]['902565be0774dd5423f5f9a050732191']['resposta'] = json_decode('[{"arquivo":"imagem_teste.png","label":"asdadsadasdasdasdasdasda"},{"arquivo":"imagem_teste.png"},{"arquivo":"imagem_teste.png"},{"arquivo":"imagem_teste.png"}]', true);
        $answers[1]['f204ed7a3ab7bfacb72664e0206568f3']['resposta'] = ["1","3"];
        $answers[1]['21ec43983b5c05221382bb35529db46d']['resposta'] = '2023-09-21';

        $answers[1]['deaa1009eaf47983f6901681a68ae12f']['comentario'] = 'Comentário show 1 !!!!';
        $answers[1]['4cb1d8267a49133df2ec0cb701d0894b']['comentario'] = 'Comentário show 2 !!!!';

        $formulario = $formularios[$data['blocoFormsForm']];

        $formatTable = ['cellMargin' => 0, 'cellMarginRight' => 0, 'cellMarginBottom' => 0, 'cellMarginLeft' => 0];
        $formatCell = ['valign' => 'center', 'borderSize' => 1, 'borderColor' => '5f5f5f'];
        $formatCell2rols = ['gridSpan' => 2, 'valign' => 'center', 'borderSize' => 1, 'borderColor' => '5f5f5f'];
        $formatCell3rols = ['gridSpan' => 3, 'valign' => 'center', 'borderSize' => 1, 'borderColor' => '5f5f5f'];
        $formatCellTitle = ['gridSpan' => 3, 'valign' => 'center', 'borderSize' => 1, 'bgColor' => 'd3d3d3', 'borderColor' => '5f5f5f'];
        $cellHCentered = ['alignment' => Jc::CENTER];
        $withCell1 = 3500;
        $withCell2 = 1000;
        $withCell3 = 4500;

        $section->addTitle('Nome do Planejamento da aplicacao - ' . $value, 2);
        $section->addTextBreak(1);

        $contentTable = $section->addTable($formatTable);
        $contentTable->addRow(1000, ['exactHeight' => true]);
        $cell1 = $contentTable->addCell($withCell1, $formatCellTitle);
        $cell1->addTextRun($cellHCentered)->addText("Coluna 1");

        foreach ($formulario as $value) {
            $answer = $answers[1][$value['data']['uuid']]['resposta'] ?? '';
            if ($value['type'] == 'multiplaEscolha') {
                // Buscar a lista de opcoes de resposta para preencher

                $comentario = $answers[1][$value['data']['uuid']]['comentario'] ?? '';
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell);
                $cell1->addText($value['data']['pergunta']);
                $cell2 = $contentTable->addCell($withCell2, $formatCell);
                $cell2->addText($answer);
                $cell3 = $contentTable->addCell($withCell3, $formatCell);
                $cell3->addText($comentario);
            }
            if ($value['type'] == 'multiplaEscolhaMultipla') {
                // Buscar a lista de opcoes de resposta para preencher

                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell3rols);
                $cell1->addText($value['data']['pergunta']);
                foreach ($answer as $valueAnswer) {
                    $cell1->addText($valueAnswer);
                }
            }
            if ($value['type'] == 'multiplaEscolhaOpcoes') {
                $opcao = $value['data']['opcoes'][$answer] ?? '';
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell3rols);
                $cell1->addText($value['data']['pergunta']);
                $cell1->addText($opcao);
            }
            if ($value['type'] == 'respostaCurta') {
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell3rols);
                $cell1->addText($value['data']['pergunta']);
                $cell1->addText($answer);
            }
            if ($value['type'] == 'respostaParagrafo') {
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell3rols);
                $cell1->addText($value['data']['pergunta']);
                $cell1->addText($answer);
            }
            if (in_array($value['type'], ['data', 'dataHora'])) {
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell3rols);
                $cell1->addText($value['data']['pergunta']);
                $cell1->addText($answer);
            }
            if ($value['type'] == 'arquivo') {
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell3rols);
                $cell1->addText($value['data']['pergunta']);
                if (!empty($answer)) {
                    foreach ($answer as $valueAnswer) {
                        $cell1->addText($valueAnswer['arquivo']);
                        if ($value['data']['solicitar_label']=='individual') {
                            $cell1->addTextRun($cellHCentered)->addText($valueAnswer['label'] ?? '');
                        }
                        $cell1->addTextBreak(1);
                    }
                    if ($value['data']['solicitar_label']=='geral') {
                        $cell1->addTextRun($cellHCentered)->addText($answer[0]['label'] ?? '');
                        $cell1->addTextBreak(1);
                    }
                }
            }
            if ($value['type'] == 'foto') {
                $contentTable->addRow(400);
                $cell1 = $contentTable->addCell($withCell1, $formatCell3rols);
                $cell1->addText($value['data']['pergunta']);
                if (!empty($answer)) {
                    foreach ($answer as $valueAnswer) {
                        $cell1->addImage('storage/forms/'. $valueAnswer['arquivo'], ['width' => 350, 'alignment' => Jc::CENTER]);
                        if ($value['data']['solicitar_label']=='individual') {
                            $cell1->addTextRun($cellHCentered)->addText($valueAnswer['label'] ?? '');
                        }
                        $cell1->addTextBreak(1);
                    }
                    if ($value['data']['solicitar_label']=='geral') {
                        $cell1->addTextRun($cellHCentered)->addText($answer[0]['label'] ?? '');
                        $cell1->addTextBreak(1);
                    }
                }
            }
        }
        $section->addTextBreak(1);
    }

    $section->addPageBreak();
}
