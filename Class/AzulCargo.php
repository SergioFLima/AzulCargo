<?php
class AzulCargo
{   
    private $amb;
    private $email;
    private $senha;
    private $url;
    private $token;

    private $cepOrigem;
    private $cepDestino;
    private $taxaColeta;
    private $valorNota;
    private $numeroPedido;
    private $sigla;
    private $pesoReal;
    private $pesoCubado;
    private $volumeTotal;
    private $baseOrigem;
    private $baseDestino;

    private $contaCorrente;
    private $tipoEntrega;
    private $formaPagamento;
    private $produtoNatureza;
    private $seguroProprio;
    private $seguroApolice;
    private $seguroSeguradora;
    private $siglaServico;
    private $unidadeDestinoSigla;
    private $unidadeOrigemSigla;
    private $observacao;
    private $taxaColetaPercentual;

    private $awb;
    private $chave;

    private $cep;

    private $cnpj;
    private $dataInicio;
    private $dataFinal;

    private $emit;
    private $toma;
    private $dest;
    private $emb = [];
    private $doc = [];
    private $itens = [];

    public function __get($prop)        {return $this->$prop;}
    public function __set($prop, $val)  {$this->$prop=$val;}

    function __construct($amb){
        $this->DB = DBCONECTA();
        $this->email = '';
        $this->senha = '';
        $this->url = $amb == 1 ? 'https://hmg.onlineapp.com.br/EDIv2_API_INTEGRACAO_Toolkit' : 'https://ediapi.onlineapp.com.br/toolkit';
   
        $body = json_encode(array('Email' => $this->email, 'Senha' => $this->senha));
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url."/api/Autenticacao/AutenticarUsuario",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
    
        $res = curl_exec($curl);
        $obj = json_decode($res, 1);
        $err = curl_error($curl);
    
        curl_close($curl);
    
        $this->token = $obj['Value'] != '' ? $obj['Value'] : $obj['ErrorText'];
    }

    public function cotar(){
        $token = $this->token;
        $cota = array(
            "Token" => $token,
            "BaseOrigem" => $this->baseOrigem,
            "CEPOrigem" => $this->cepOrigem,
            "BaseDestino" => $this->baseDestino,
            "CEPDestino" => $this->cepDestino,
            "PesoCubado" => $this->pesoCubado,
            "PesoReal" => $this->pesoReal,
            "Volume" => $this->volumeTotal,
            "ValorTotal" => $this->valorNota,
            "Pedido" => $this->numeroPedido,
            "SiglaServico" => $this->sigla,
            "TaxaColeta" => $this->taxaColeta,
            "Itens" => $this->itens
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url."/api/Cotacao/Enviar",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($cota),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
    
        $res = curl_exec($curl);
        $obj = json_decode($res, 1);
        $err = curl_error($curl);
    
        curl_close($curl);
        return $obj;
    }

    public function emitirAWB(){

        $token = $this->token;
        $documento = $this->doc;
        $embalagem = $this->emb;

        $participante = array(
            $this->dest,
            $this->emit,
            $this->toma
        );
        $awb = array(
            "Token" => $token,
            "ContaCorrente" => $this->contaCorrente,
            "TipoEntrega" => $this->tipoEntrega,
            "FormaPagamento" => $this->formaPagamento,
            "ListaDocumentos" => $documento,
            "ListaEmbalagens" => $embalagem,
            "ListaParticipantes" => $participante,
            "ProdutoNatureza" => $this->produtoNatureza,
            "SeguroProprio" => $this->seguroProprio,
            "SeguroApolice" => $this->seguroApolice,
            "SeguroSeguradora" => $this->seguroSeguradora,
            "SiglaServico" => $this->siglaServico,
            "UnidadeDestinoSigla" => $this->unidadeDestinoSigla,
            "UnidadeOrigemSigla" => $this->unidadeOrigemSigla,
            "Observacao" => $this->observacao,
            "TaxaColetaPercentual" => $this->taxaColetaPercentual,
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url."/api/EmissaoAWB/Enviar",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($awb),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
    
        $res = curl_exec($curl);
        $obj = json_decode($res, 1);
        $err = curl_error($curl);
    
        curl_close($curl);
        return $obj;
    }

    public function rastrear(){
        $token = $this->token;
        $rastreio = array(
            "Token" => $token,
            "Awb" => $this->awb,
            "ChaveNfe" => $this->chave,
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url."/api/Rastreio/Consultar",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($rastreio),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
    
        $res = curl_exec($curl);
        $obj = json_decode($res, 1);
        $err = curl_error($curl);
    
        curl_close($curl);
        return $obj;
    }

    public function unidade_proxima(){
        $token = $this->token;
        $local = array(
            "Token" => $token,
            "Cep" => $this->cep,
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url."/api/Unidades/LocalizarUnidades",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($local),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
    
        $res = curl_exec($curl);
        $obj = json_decode($res, 1);
        $err = curl_error($curl);
    
        curl_close($curl);
        return $obj;
    }

    public function consultar_faturas(){
        $token = $this->token;
        $faturas = array(
            "Token" => $token,
            "Cnpj" => $this->cnpj,
            "DataInicio" => $this->dataInicio,
            "DataFinal" => $this->dataFinal
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url."/api/FaturasOnline/BuscarFaturas",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($faturas),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
    
        $res = curl_exec($curl);
        $obj = json_decode($res, 1);
        $err = curl_error($curl);
    
        curl_close($curl);
        return $obj;
    }

    public function emitir_etiqueta(){
        $token = $this->token;
        $local = array(
            "Token" => $token,
            "Awb" => $this->awb,
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url."/api/ImpressaoEtiqueta/EmitirEtiqueta",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => json_encode($local),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
    
        $res = curl_exec($curl);
        $obj = json_decode($res, 1);
        $err = curl_error($curl);
    
        curl_close($curl);
        return $obj;
    }

    public function download_xml(){
        $token = $this->token;
        $download = array(
            "Token" => $token,
            "Awb" => $this->awb,
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url."/api/DownloadCte/DownloadXML",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => json_encode($download),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
    
        $res = curl_exec($curl);
        $obj = json_decode($res, 1);
        $err = curl_error($curl);
    
        curl_close($curl);
        return $obj;
    }

    public function ocorrencias($numero = null){
        $ocorrencias = array(
            1 => 'ENTREGA REALIZADA NORMALMENTE',
            2 => 'ENTREGA FORA DA DATA PROGRAMADA',
            3 => 'RECUSA POR FALTA DE PEDIDO DE COMPRA',
            4 => 'RECUSA POR PEDIDO DE COMPRA CANCELADO',
            5 => 'FALTA DE ESPACO FISICO NO DEPOSITO DO CLIENTE  DE DESTINO',
            6 => 'ENDERECO DO CLIENTE DESTINO NAO FOI LOCALIZADO.',
            7 => 'DEVOLUCAO NAO AUTORIZADA PELO CLIENTE',
            8 => 'PRECO DA MERCADORIA EM DESACORDO COM O PEDIDO DE COMPRA',
            9 => 'MERCADORIA EM DESACORDO COM O PEDIDO DE COMPRA',
            10 => 'CLIENTE DESTINO SOMENTE RECEBE MERCADORIA COM FRETE PAGO',
            11 => 'RECUSA POR DEFICIENCIA EMBALAGEM MERCADORIA',
            12 => 'REDESPACHO NAO INDICADO',
            13 => 'TRANSPORTADORA NAO ATENDE A CIDADE DO CLIENTE DESTINO',
            14 => 'MERCADORIA SINISTRADA',
            15 => 'EMBALAGEM SINISTRADA',
            16 => 'PEDIDO DE COMPRAS EM DUPLICIDADE',
            17 => 'MERCADORIA FORA DA EMBALAGEM DE ATACADISTA',
            18 => 'MERCADORIAS TROCADAS',
            19 => 'REENTREGA SOLICITADA PELO CLIENTE.',
            20 => 'ENTREGA PREJUDICADA POR HORARIO/FALTA DE TEMPO HABIL',
            21 => 'ESTABELECIMENTO FECHADO.',
            22 => 'REENTREGA SEM COBRANCA DO CLIENTE',
            23 => 'EXTRAVIO DE MERCADORIA EM TRANSITO',
            24 => 'MERCADORIA REENTREGUE AO CLIENTE DESTINO',
            25 => 'ENVIO DEVOLVIDO AO CLIENTE DE ORIGEM',
            26 => 'NOTA FISCAL RETIDA PELA FISCALIZACAO',
            27 => 'ROUBO DE CARGA',
            28 => 'MERCADORIA RETIDA ATE SEGUNDA ORDEM',
            29 => 'CLIENTE RETIRA MERCADORIA NA TRANSPORTADORA',
            30 => 'PROBLEMA COM A DOCUMENTACAO (NOTA FISCAL / CTRC)',
            31 => 'ENTREGA COM INDENIZACAO EFETUADA',
            32 => 'FALTA COM SOLICITACAO DE REPOSICAO',
            33 => 'FALTA COM BUSCA/RECONFERENCIA',
            34 => 'CLIENTE FECHADO PARA BALANCO.',
            35 => 'QUANTIDADE DE PRODUTO EM DESACORDO (NOTA FISCAL E/OU PEDIDO)',
            36 => 'BAIXA POR SINISTRO',
            37 => 'BAIXA FISCAL',
            38 => 'BAIXA SEM COMPROVANTE (DO AGENTE)',
            41 => 'PEDIDO DE COMPRA INCOMPLETO',
            42 => 'NOTA FISCAL COM PRODUTOS DE SETORES DIFERENTES',
            43 => 'FERIADO LOCAL/NACIONAL',
            44 => 'EXCESSO DE VEICULOS',
            45 => 'CLIENTE DESTINO ENCERROU ATIVIDADES',
            46 => 'RESPONSAVEL DE RECEBIMENTO AUSENTE',
            47 => 'CLIENTE DESTINO EM GREVE',
            48 => 'AEROPORTO FECHADO NO DESTINO',
            49 => 'VOO CANCELADO',
            50 => 'GREVE NACIONAL (GREVE GERAL)',
            51 => 'MERCADORIA VENCIDA (DATA DE VALIDADE EXPIRADA)',
            52 => 'MERCADORIA REDESPACHADA (ENTREGUE PARA REDESPACHO)',
            55 => 'LOCAL DE ENTREGA COM RESTRIÇÃO',
            60 => 'ENDEREÇO DE ENTREGA ERRADO',
            65 => 'ENTRAR EM CONTATO COM O COMPRADOR',
            66 => 'TROCA NAO DISPONIVEL',
            67 => 'FINS ESTATISTICOS',
            68 => 'DATA DE ENTREGA DIFERENTE DO PEDIDO',
            69 => 'SUBSTITUICAO TRIBUTARIA',
            70 => 'SISTEMA FORA DO AR.',
            71 => 'CLIENTE DESTINO NAO RECEBE PEDIDO PARCIAL',
            72 => 'CLIENTE DESTINO SO RECEBE PEDIDO PARCIAL',
            73 => 'REDESPACHO SOMENTE COM FRETE PAGO',
            74 => 'FUNCIONARIO NAO AUTORIZADO A RECEBER MERCADORIAS',
            75 => 'MERCADORIA EMBARCADA PARA ROTA INDEVIDA',
            76 => 'ESTRADA/ENTRADA DE ACESSO INTERDITADA',
            77 => 'CLIENTE DESTINO MUDOU DE ENDERECO',
            78 => 'AVARIA TOTAL',
            79 => 'AVARIA PARCIAL',
            80 => 'EXTRAVIO TOTAL',
            81 => 'EXTRAVIO PARCIAL',
            82 => 'SOBRA DE MERCADORIA SEM NOTA FISCAL',
            83 => 'MERCADORIA EM PODER DA SUFRAMA PARA INTERNACAO',
            84 => 'MERCADORIA RETIRADA PARA CONFERENCIA',
            85 => 'APREENSAO FISCAL DA MERCADORIA',
            86 => 'EXCESSO DE CARGA/PESO',
            87 => 'DESTINATÁRIO EM FÉRIAS COLETIVAS',
            88 => 'RECUSA AGUARDANDO NEGOCIAÇÃO',
            91 => 'ENTREGA PROGRAMADA.',
            92 => 'PROBLEMAS FISCAIS',
            93 => 'FALTA PARCIAL DE CARGA.',
            94 => 'FALTA TOTAL DE CARGA.',
            95 => 'FALTA DO CONHECIMENTO.',
            96 => 'FALTA DE NOTA FISCAL.',
            97 => 'RECOLHIMENTO DA CARGA O SETOR DE PROCESSOS',
            98 => 'RO ENVIADA A WINE',
            99 => 'OUTROS TIPOS DE OCORRENCIAS NAO ESPECIFICADOS ACIMA',
            100 => 'EMISSAO DO CONHECIMENTO DE TRANSPORTE.',
            101 => 'ALTERACAO DO CONHECIMENTO DE TRANSPORTE.',
            102 => 'IMPRESSAO DO CONHECIMENTO DE TRANSPORTE',
            103 => 'IMPRESSAO DAS ETIQUETAS DO CONHECIMENTO.',
            104 => 'EMISSAO DE MANIFESTO DE SAIDA.',
            105 => 'ENTRADA DO MANIFESTO NA UNIDADE.',
            106 => 'EMISSAO DA LISTAGEM DE ENTREGAS.',
            107 => 'CANCELAMENTO DO CONHECIMENTO.',
            108 => 'REIMPRESSAO DE CONHECIMENTO.',
            109 => 'COD FECHOU E NAO DEU BAIXA DE ENTREGA',
            110 => 'COD FOI VENDIDO E NAO DEU BAIXA DE ENTREGA',
            111 => 'UNIDADE REATIVADA',
            112 => 'CARGA RECEBIDA EM VOO FORA DA ROTERIZAÇÃO',
            113 => 'RETORNO DE EDI COM CONHECIMENTOS EMITIDOS.',
            114 => 'RETORNO DE EDI COM OCORRENCIAS DE ENTREGA.',
            115 => 'RETORNO DE EDI COM FATURAS PROCESSADAS.',
            116 => 'BAIXA DA LISTAGEM DE ENTREGAS.',
            117 => 'REMOCAO DO AWB DA LISTAGEM DE ENTREGAS.',
            118 => 'BAIXA FORCADA PELO SISTEMA',
            119 => 'ALTERACAO NA MODALIDADE DE PAGAMENTO',
            120 => 'AGUARDANDO RETORNO DO COMPROVANTE DE ENTREGA.',
            121 => 'IMPRESSAO DA ETIQUETA DO ENVELOPE DE COMPROVANTES.',
            122 => 'EMISSAO DA RELACAO DE ENVELOPES DE COMPROVANTES.',
            123 => 'ENTRADA DA RELACAO DE ENVELOPES DE COMPROVANTES.',
            124 => 'BAIXA DA RELACAO DE ENVELOPES DE COMPROVANTES.',
            125 => 'ENTREGA DO COMPROVANTE PARA O CLIENTE.',
            126 => 'TRANSFERENCIA DE UNIDADE FORCADA.',
            127 => 'UNITIZACAO DA CARGA.',
            128 => 'DESUNITIZACAO DA CARGA',
            129 => 'CONHECIMENTO NAO RECEBIDO PELA DESUNITIZACAO DA CARGA.',
            130 => 'CARGA LOCALIZADA INTEGRALMENTE',
            131 => 'CARGA LOCALIZADA PARCIALMENTE',
            132 => 'MUDANCA DE OFF-LOAD',
            133 => 'SAIDA DO VEICULO',
            134 => 'SAIDA DO VOO',
            135 => 'EMBARQUE DE CARGA EM ULD',
            136 => 'CHEGADA DO VEICULO',
            137 => 'CHEGADA DO VOO',
            138 => 'EMBARQUE DE CARGA NO VOO',
            139 => 'ABRIR VEICULO PARA CONFERENCIA',
            142 => 'CARREGAMENTO DO VEICULO',
            143 => 'RECEBER E POSIOCIONAR PARA TRIAGEM',
            144 => 'EM PROCESSO DE CONFERENCIA',
            145 => 'O USUARIO ALTEROU O AWB ANTES DA IMPRESSAO',
            146 => 'O USUARIO REIMPRIMIU O MANIFESTO DE CARGAS',
            150 => 'EMISSAO AWB PRIMITIVO',
            160 => 'EM ANALISE PARA CANCELAMENTO.',
            170 => 'CANCELAMENTO RECUSADO.',
            171 => 'FECHAMENTO DA LISTAGEM DE CARGA DO VEICULO',
            172 => 'SAIDA AUTORIZADA DO VEICULO DE ENTREGA',
            173 => 'CANCELAMENTO DA LISTAGEM DE CARGA DO VEICULO',
            180 => 'CARGA EXTRAVIADA - EM PROCESSO DE BUSCA',
            181 => 'CARGA AVARIADA - EM PROCESSO DE APURACAO',
            182 => 'CARGA VIOLADA - EM PROCESSO DE APURACAO',
            183 => 'CARGA ROUBADA - EM PROCESSO DE APURACAO',
            184 => 'ATRASO NA ENTREGA - EM PROCESSO DE APURACAO',
            185 => 'PROCESSO DE INDENIZACAO INICIADO PELA BASE',
            186 => 'PROCESSO DE INDENIZACAO CANCELADO',
            187 => 'PROCESSO DE INDENIZACAO EM ANALISE',
            188 => 'PROCESSO DE INDENIZACAO ENCAMINHADO PARA PAGAMENTO',
            189 => 'PROCESSO DE INDENIZACAO ENCAMINHADO PARA SEGURADO',
            190 => 'INDENIZACAO PAGA AO CLIENTE',
            191 => 'INDENIZACAO JULGADA IMPROCEDENTE',
            192 => 'INDENIZACAO SUSPENSA - CARGA LOCALIZADA',
            200 => 'POSICIONAMENTO DE CARGA EM LOCATION',
            201 => 'CONEXAO IMEDIATA DE VOO.',
            202 => 'STOCK',
            203 => 'ALLOCATED',
            204 => 'LOAD',
            205 => 'INFLIGHT',
            206 => 'UNLOAD',
            207 => 'MAINTENANCE/LOAN',
            208 => 'OUT OF SERVICE',
            209 => 'SCRAP',
            210 => 'LOST/STOLEN',
            211 => 'ULD DISABLED',
            212 => 'ULD RE-ENABLED',
            250 => 'CORTE DE CARGA DO VEICULO',
            251 => 'RETIRADA DE CARGA DO VEICULO',
            252 => 'CORTE DE CARGA DO VOO',
            253 => 'RETIRADA DE CARGA DO VOO',
            254 => 'RETIRADA DE CARGA DA ULD',
            255 => 'DESEMBARQUE DE CARGA DA ULD',
            256 => 'ULD LACRADA',
            257 => 'ULD DESLACRADA',
            301 => 'EMISSAO DE MANIFESTO PARA ANALISE DE FATURAMETO',
            302 => 'ALTERACAO DE CONHECIMENTO REJEITADO EMITIDO EM FSDA.',
            303 => 'EMISSAO DE CT-E COM EPEC. EVENTO REGISTRADO.',
            304 => 'EMISSAO DE CT-E COM EPEC. USO AUTORIZADO.',
            350 => 'BAIXA DE CONHECIMENTO DESFEITA',
            375 => 'ALTERACAO DE CT-E.',
            376 => 'ALTERACAO DE COURIER.',
            400 => 'DANO/AVARIA PERMISSÍVEL',
            401 => 'DANO/AVARIA NÃO PERMISSÍVEL',
            402 => 'ULD ENVIADO PARA REPARO',
            403 => 'ULD REPARADO',
            404 => 'MISSING ULD',
            405 => 'INVENTARIO',
            406 => 'OUTRAS OCORRENCIAS',
            425 => 'COTACAO INCLUIDA.',
            426 => 'COTACAO EDITADA.',
            450 => 'COLETA INCLUIDA.',
            451 => 'COLETA SINISTRADA.',
            452 => 'COLETA RECUSADA.',
            453 => 'COLETA EM ANDAMENTO.',
            454 => 'COLETA CANCELADA',
            455 => 'COLETA SOLICITADA',
            456 => 'COLETA ALTERADA',
            457 => 'COLETA ACEITA',
            458 => 'COLETA PENDENTE',
            459 => 'COLETA REALIZADA',
            460 => 'MINUTA INCLUIDA',
            461 => 'MINUTA EDITADA',
            462 => 'MINUTA UTILIZADA NA EMISSAO',
            500 => 'PRODUTO NAO ENTRA NA RESIDENCIA',
            501 => 'COLETA PRODUTO MONTADO',
            502 => 'COLETA TROCA CASADA',
            503 => 'REVERSA - COLETADO',
            504 => 'REVERSA - NOTA DE COLETA EXTRAVIADA',
            505 => 'AUSENTE 1º TENTATIVA',
            506 => 'AUSENTE 2º TENTATIVA',
            507 => 'AUSENTE 3º TENTATIVA',
            508 => 'LOJA RECUSOU PROCESSO DE COLETA',
            509 => 'LOJA FECHADA',
            510 => 'DADOS INSUFICIENTES PARA ENTREGA',
            511 => 'DESTINATARIO DESCONHECIDO NO LOCAL',
            512 => 'RECUSADO POR TERCEIRO',
            513 => 'EXCESSO DE CHUVA',
            514 => 'DIFICULDADES NA REGIAO DE ENTREGA',
            515 => 'LOJA FECHADA ENTREGA',
            516 => 'DIFICULDADES NA REGIÃO DE COLETA',
            517 => 'PASSAGEM PELA FISCALIZACAO',
            518 => 'PEDIDO RECUSADO',
            519 => 'RECUSA POR FALTA DE AGENDAMENTO',
            520 => 'FALTA DE ESPAÇO FÍSICO NO DESTINATÁRIO',
            521 => 'PREÇO DA MERCADORIA EM DESACORDO COM O PEDIDO',
            522 => 'AGUARDANDO AGENDAMENTO',
            523 => 'ACIDENTE',
            524 => 'EXTRAVIO DE DOCUMENTOS',
            525 => 'NOTA FISCAL LIBERADA DA FISCALIZAÇÃO',
            526 => 'VOO CANCELADO',
            527 => 'AGUARDANDO RETIRADA DO OBJETO',
            528 => 'CONTATO COM O CLIENTE',
            529 => 'TROCA DE OFF LOAD',
            531 => 'ATENDIMENTO CALL CENTER  (EXCLUSIVO CALL CENTER)',
            532 => 'OCORRENCIA WINE  (USO EXCLUSIVO  SOL)',
            533 => 'ENCAMINHADO PARA SEFAZ',
            534 => 'LIBERADO PELA SEFAZ',
            535 => 'WINE FINALIZAÇÃO PROCESSO INDENIZATÓRIO',
            536 => 'EM PROCESSO DE LIBERAÇÃO SUFRAMA',
            537 => 'CLIENTE DESCONHECIDO NO LOCAL',
            538 => 'CARGA RECEBIDA PARCIAL NO PA',
            539 => 'CARGA NAO RECEBIDA NO PA',
            540 => 'CARGA RETIDA PARA VERIFICAÇÃO',
            600 => 'INDENIZAÇÃO PAGA EM 16/01/2019',
            601 => 'CT-E CANCELADO POR REJEICAO.',
            602 => 'PROBLEMAS COM VEÍCULO (QUEBRA/ACIDENTE/RASTREADOR)',
            603 => 'CHEGADA ANTECIPADA DEVIDO REQUERIMENTO COMERCIAL SAMSUNG',
            604 => 'CHEGADA ANTECIPADA DEVIDO A REQUERIMENTO DO CLIENTE',
            605 => 'FALTA DO DOCUMENTO XML',
            606 => 'AGENDAMENTO PARCIAL DO SHIPMENT/MANIFESTO',
            607 => 'AGENDAMENTO FORA DO DIA FIXO',
            608 => 'DIVERGÊNCIA DE BOOKING',
            609 => 'DIFERENTES PRODUTOS NO MESMO CAMINHÃO',
            610 => 'FALTA DE VEÍCULO',
            611 => 'EMBARCADA PARA DESTINO INCORRETO',
            612 => 'DIFERENTES CLIENTES NO MESMO CAMINHÃO',
            613 => 'ERRO IOD (REVERTIDO)',
            614 => 'FALHA NO PLANEJAMENTO',
            615 => 'PERDA DE DOCUMENTAÇÃO  (NF /CTE)',
            616 => 'ERRO IOD (SEM REVERSÃO)',
            617 => 'RECUSA POR FALTA DE PAGAMENTO DE DESCARGA',
            618 => 'ATRASO DE COLETA',
            619 => 'ATRASO NO TRANSIT TIME',
            620 => 'AVARIA DE EMBALAGEM',
            621 => 'ENTREGA FORA DO PADRÃO DO CLIENTE',
            622 => 'FALTA COM BUSCA/RECONFERÊNCIA',
            623 => 'FALTA COM BUSCA/RECONFERÊNCIA',
            624 => 'MERCADORIAS TROCADAS (FÍSICO DIFERENTE DA NF) NA TRANSPORTAD',
            625 => 'PROBLEMA COM A DOCUMENTAÇÃO (CTRC/NF/PIN)',
            626 => 'ATRASO TRANSIT TIME 1° LEG',
            627 => 'DIVERGÊNCIA NA CRIAÇÃO DE SM (SOLICITAÇÃO DE MONITORAMENTO)',
            628 => 'CARGA DISPONÍVEL PARA ENTREGA',
            629 => 'DAMAGE (CLAIM)',
            630 => 'VALIDAÇÃO DE NOTA FISCAL',
            631 => 'INSPEÇÃO DE PRODUTO (TESTE)',
            632 => 'DIVERGÊNCIA NO PORTAL DO CLIENTE (AGENDA/PEDIDO /VALORES)',
            633 => 'MÃO DE OBRA SOLICITADA PARA DESCARGA',
            634 => 'RETIDO NA SEFAZ - PAGAMENTO DE ICMS',
            635 => 'DIVERGENCIA DE FATURAMENTO (IMPOSTO, MODELO, PEDIDO)',
            636 => 'DIVERGENCIA ENTRE ACTUAL IOD E POD',
            637 => 'DEMORA NA DESCARGA (APOS FREE TIME)',
            638 => 'AGENDAMENTO CANCELADO/ALTERADO PELO CLIENTE',
            639 => 'DIVERGÊNCIA INTERNA DE RECEBIMENTO DO CLIENTE',
            640 => 'FALTA DE ESPAÇO FÍSICO NO ARMAZÉM DO CLIENTE',
            641 => 'COMPROVANTE DE ENTREGA RETIDO PARA CONFERENCIA DA CARGA',
            642 => 'CLIENTE AUSENTE',
            643 => 'ACIMA DA CAPACIDADE AÉREA',
            644 => 'DIVERGÊNCIA DE IMPOSTOS (FATURAMENTO)',
            645 => 'DIVERGÊNCIA NO CADASTRO DO CLIENTE (CNPJ/EAN/ENDEREÇO)',
            646 => 'DIVERGENCIA NO PEDIDO DE COMPRA (PRECO/QUANTIDADE/MODELO)',
            647 => 'BLOQUEIO FINANCEIRO',
            648 => 'POD ESCANEADO VALIDADO RECEBIDO DA TRANSPORTADORA',
            649 => 'INSPEÇÃO DE QUALIDADE',
            650 => 'FALTA DE ACESSÓRIOS',
            651 => 'NOTA FISCAL RETIDA PELA FISCALIZACAO (CONFERÊNCIA) POR CAUSA',
            652 => 'RETIDO - SUFRAMA',
            653 => 'ESTRADA BLOQUEADOS / ENTRADA / ACESSO FECHADO',
            654 => 'GREVE EM ÓRGÃOS DO GOVERNO',
            655 => 'NOTA FISCAL RETIDA PELA FISCALIZACAO (CONFERÊNCIA)',
            656 => 'CAUSAS NATURAIS',
            657 => 'OTIMIZAÇÃO DE AGENDA',
            658 => 'DIVERGÊNCIA DE PROGRAMAÇÃO',
            659 => 'AVARIA DE CONTAINER',
            660 => 'INSPEÇÃO DE CONTAINER',
            661 => 'PROBLEMA SISTÊMICO',
            662 => 'FALTA DE ESCOLTA',
            663 => 'ATRASO DE ESCOLTA',
            664 => 'PROBLEMA LIBERAÇÃO BAÚ/VEÍCULO (SISTEMA)',
            665 => 'ERRO DE SISTEMA (SAP/ OU TSS)',
            666 => 'AVARIA DE EMBALAGEM TOTAL',
            667 => 'AVARIA DE EMBALAGEM PARCIAL',
            668 => 'CODIGO EAN INCORRETO',
            669 => 'RECUSA POR PRODUTO FORA DO PADRÃO DE PALETIZAÇÃO',
            700 => 'ATRASO NA EXPEDICAO',
            777 => 'CORRECAO ICMS',
            800 => 'EM TRAMITE ADUANEIRO NA ORIGEM',
            801 => 'LIBERADA - AGUARDANDO VOO INTERNACIONAL',
            802 => 'EM TRAMITE ADUANEIRO NO DESTINO',
            803 => 'LIBERADA - TRANSFERENCIA DOMESTICA',
            804 => 'EM ROTA PARA ENTREGA',
            805 => 'DOCUMENTAÇÃO INCORRETA/INCOMPLETA',
            806 => 'RETIDA PELA ADUANA LOCAL',
            807 => 'INADIMITIDA PELA ADUANA LOCAL',
            808 => 'EM PROCESSO DE DEVOLUÇÃO MERCADO LIVRE',
            809 => 'PACOTE PERDIDO PELO PARCEIRO INTL',
            998 => 'BAIXA POR DUPLICIDADE EDI',
            999 => 'REMARK DA CARGA',
            1000 => 'DEVOLUÇÃO SOLICITADA PELO CLIENTE - FRAUDE',
            1010 => 'BAIXA REALIZADA CONFORMA INVENTARIO',
            1108 => 'REIMPRESSAO DE ETIQUETAS',
            1109 => 'ENTREGA BLOQUEADA PELO REMETENTE',
            2626 => 'NOTA FISCAL RETIDA PELA FISCALIZACAO MERCADO LIVRE',
            7001 => 'MERCADORIAS TROCADAS (FÍSICO DIFERENTE DA NF) NO ARMAZÉM',
        );
        if(isset($numero)){
            return $ocorrencias[$numero];
        }
        else {
            return $ocorrencias;
        }
    }

    function emit($var){
        $this->emit = array(
            "Tipo" => "Emitente",
            "CnpjCpf" => $var->cnpj,
            "Contato" => [],
            "Endereco" => [],
            "Estado" => $var->UF,
            "IENumero" => $var->IE,
            "Nome" => $var->nome
        );
    }

    function dest($var){
        $this->dest = array(
            "Tipo" => "Destinatario",
            "CnpjCpf" => $var->cnpj,
            "Contato" => [],
            "Endereco" => [],
            "Estado" => $var->UF,
            "IENumero" => $var->IE,
            "Nome" => $var->nome
        );
    }

    function toma($var){
        $this->toma = array(
            "Tipo" => "Tomador",
            "CnpjCpf" => $var->cnpj,
            "Contato" => [],
            "Endereco" => [],
            "Estado" => $var->UF,
            "IENumero" => $var->IE,
            "Nome" => $var->nome
        );
    }

    function emb($var){
        $conta = count($this->emb) + 1;
        $this->emb[] = array(
            "Altura" => $var->altura,
            "Largura" => $var->largura,
            "Comprimento" => $var->comprimento,
            "ItemNumero" => $conta,
            "PesoRealUnitario" => $var->peso,
            "Quantidade" => $var->quantidade
        );
    }

    function item($var){
        $this->itens[] = array(
            "Volume" => $var->volumes,
            "Peso" => $var->peso,
            "Altura" => $var->altura,
            "Comprimento" => $var->comprimento,
            "Largura" => $var->largura
        );
    }

    function doc($var){
        $this->doc[] = array(
            "Tipodocumento" => $var->tipo,
            "DataEmissao" => $var->dataEmissao.".000",
            "ValorTotal" => $var->valor,
            "ChaveAcesso" => $var->chave,
            "PinSuframa" => $var->suframa
        );
    }
}
