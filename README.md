### [INTERNAL DOCS](./docs/00-index.md)

Aula:
- [x] No Planejamento (audit-form-lists) o status de preenchimento nao esta correto (atualizar a base após ter alguma resposta)
- [x] Agrupar município sede com prestador e excluir a coluna de cidade desta tabela (AgencyProviderResource)
- [x] Concatenar o estado na base de cidades no campo de selacao
- [x] Pensar na estrutura das pastas onde ficarão os arquivos e fotos dos formulários. Cada agencia com a sua pasta (Tenant?).
- [x] Como usar o PHPWord ou outra biblioteca para gerar os relatórios
- [x] Como criar uma pagina que consiga acessar o banco de dados?
- [x] Colocar o botão de visualizar / exportar relatório
- [ ] Como alterar o dashboard no Filament?
    - [ ] Criar um widget de calendário e depois abrir ele no dashboard
    - [ ] Incluir as auditorias no calendário
    - [ ]
- [ ] Dúvida, o arquivo GerarRelatorioAction.php nao será apagado por estar dentro da pasta vendor?
- [ ] Colocar o status no plano de ação
- [x] Alterar o nome do Plan para Plano de Ação
- [ ] A parte de excluir as respostas dos formulários nao esta funcionando. Ele exclui e depois gera erro na base
- [x] Retirar o nome indice do indice
- [x] Colocar a pergunta em negrito no relatório
- [x] Inserir nas configurações do relatório o texto que vai aparecer no rodapé
- [x] Possibilidade de alterar a logo do vamosfiscalizae pela logo da agencia
- [x] Inserir botão para responder o formulário

Michel
- [ ] nl2br nao funciona. Talvez tenha que engodar o texto antes de salvar. Markdown funcionou na tabela, mas nao na descricao
- [ ] A agencia ja pode vir selecionada no cadastro de prestador e em todos os demais telas que pede (Tenant?)
- [ ] A logo do sistema altera, mas depois volta a antiga no modo escuro (nao funciona na V3)
- [ ] Usar um icones diferentes (arquivo svg).  Como colocar estes outros ícones?
- [ ] Colocar a árvore de forma visual (TreeView Plugin?)
- [ ] Inserir opção do usuário colocar seu avatar
- [ ] Instalar o Tenant


Fausto
- [x] Inserir os blocos (Temas) nas respostas dos formulários (o melhor seria criando Sections?)
- [x] Incluir no plano de ação o tipo da ação (Não conformidade ou Recomendação ou outros tipos)
- [x] Colocar todas as perguntas de múltipla escolha com opção de colocar uma observação
- [ ] Ter opção de colocar o titulo resumido do formulário para mostrar no gráfico
- [x] Implantar a estrutura das pastas onde ficarão os arquivos e fotos dos formulários
- [x] Criar a model/resource para construir o relatório
- [x] Criar o relatório e exportar para word/html











Perfis
- Administrador Global do sistema
	Seria o meu acesso, que teria como ver todas as agencias que estão usando o sistema e gerenciar os acessos.
    - [ ] Alterar as configurações do sistema
    - [ ] Editar serviços (ServiceResource)
    - [ ] Editar agências (AgencyResource)
    - [ ] Cadastrar novas agências na base. Novos clientes! (Tenant)
    - [ ] Editar cidades (CityResource)
    - [ ] Editar Prestadores (ProviderResource)
    - [ ] Editar os Status
    - [ ] Tipos de ações (Não conformidade ou Recomendação ou outros tipos)



- Usuário Administrador
	Seria uma pessoa da agencia selecionada para fazer a gestão dos usuários da empresa
    - [ ] Cadastro e gestão de usuários da respectiva agencia
    - [ ] Editar os dados da sua agência (prioridade baixa)
    - [ ] Possibilidade de selecionar prestadores da base geral para a sua base (AgencyProviderResource)
    - [ ] Possibilidade de selecionar municípios da base geral para a sua base (AgencyCityResource)
    - [ ] Editar e criar árvores (TreeResource)
    - [ ] Editar e criar o conteúdo das árvores (ContentTreeResource)
    - [ ] Editar e Criar lista de Opções de Resposta que forem diferente das padronizadas (FormOptionResource)
    - [ ] Editar e criar novos formulários (AuditFormResource)
    - [ ] Planejamento da aplicação dos formulários (AuditFormListResource)
    - [ ] Responder os formulários (AnswerResource)
    - [ ] Editar e criar novas Ações do Plano de Ação da Fiscalização  (PlanResource) - Dentro do Planejamento
    - [ ] *Gerar relatórios (prioridade alta)
    - [ ] Fazer comparações (prioridade baixa)




- Usuário Geral
	Usuários do sistema dentro da agencia
    - [ ] Editar e criar árvores (TreeResource) - ver se pode somente visualizar a árvore para editar somente o conteúdo
    - [ ] Editar e criar o conteúdo das árvores (ContentTreeResource)
    - [ ] Planejamento dos formulários (AuditFormListResource)
    - [ ] Responder os formulários (AnswerResource)
    - [ ] Editar e criar novas Ações do Plano de Ação da Fiscalização  (PlanResource) - Dentro do Planejamento
    - [ ] Fazer comparações (prioridade baixa)



Bases que precisam ser separadas por agência (Tenant):
    - [ ] User
    - [ ] Tree
    - [ ] FormOption (Ter o padrão do sistema, mas cada agencia pode alterar)
    - [ ] Cidades atendidas
    - [ ] Prestadores Atendidos


Criar um banco de dados para cada agencia ou um global




Elaborar os Relatórios:
- [ ] Seleciona o Município, Prestador, Serviço e pede para mostrar os formulários
- [ ] Seleciona o período que serão mostrados os formulários
    - [ ] Seleciona os formulários
        - [ ] Seleciona as árvores e os níveis do relatório que existem formulários (possibilidade de ordenar a sequencia de exibicao)

Montar o relatório
- O formulário é o título
- O nível é o subtítulo (concatenando todos os níveis)
- Ter opção de colocar o titulo resumido do formulário para mostrar no gráfico
- O gráfico de inconformidades será agrupando os formulários pelo titulo resumido
-
- Ter uma parte para poder criar os blocos do relatório
- Poder selecionar se coloca uma tabela
