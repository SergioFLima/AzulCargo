<?php
require_once('Class/AzulCargo.php');

if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'cotar'){
    $azul = new AzulCargo(1);
    $azul->baseOrigem = "";
    $azul->cepOrigem = "74705270";
    $azul->baseDestino = "";
    $azul->cepDestino = "77001016";
    $azul->pesoCubado = 100;
    $azul->pesoReal = 100;
    $azul->volumeTotal = 1;
    $azul->valorNota = 1500;
    $azul->numeroPedido = "";
    $azul->taxaColeta = true;
    $azul->sigla = '';

    $item = new stdClass();
    $item->volumes = 1;
    $item->peso = 100;
    $item->altura = 15;
    $item->comprimento = 10
    $item->largura = 5;
    $azul->item($item);

    $ret = $azul->cotar(); 
    echo json_encode($ret);
}

if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'emitirAWB') {
    $azul = new AzulCargo(1);

    $azul->contaCorrente = "17082317185758";
    $azul->tipoEntrega = "Aeroporto";
    $azul->formaPagamento = "PP";
    $azul->produtoNatureza = "GERAL";
    $azul->seguroProprio = "1";
    $azul->seguroApolice = "0552100102";
    $azul->seguroSeguradora = "1";
    $azul->siglaServico = "AMANHA";
    $azul->unidadeDestinoSigla = "MAO";
    $azul->unidadeOrigemSigla = "FOR";
    $azul->observacao = "Teste";
    $azul->taxaColetaPercentual = "1";

    $dest = new stdClass();
    $dest->cnpj = "06626253075089";
    $dest->UF = "AM";
    $dest->IE = "053618076";
    $dest->nome = "EMPREENDIMENTOS PAGUE MENOS SA";
    $azul->dest($dest);

    $emit = new stdClass();
    $emit->cnpj = "06626253012400";
    $emit->UF = "CE";
    $emit->IE = "62721593";
    $emit->nome = "EMPREENDIMENTOS PAGUE MENOS SA";
    $azul->emit($emit);

    $toma = new stdClass();
    $toma->cnpj = "06626253012400";
    $toma->UF = "CE";
    $toma->IE = "62721593";
    $toma->nome = "EMPREENDIMENTOS PAGUE MENOS SA";
    $azul->toma($toma);

    $emb = new stdClass();
    $emb->altura = 100;
    $emb->largura = 20;
    $emb->comprimento = 15;
    $emb->peso = 100;
    $emb->quantidade = 1;
    $azul->emb($emb);

    $doc = new stdClass();
    $doc->tipo = "NFe";
    $doc->dataEmissao = (new DateTime())->format('Y-m-d H:i:s');
    $doc->valor = 700;
    $doc->chave = "52210502138006001399550010000011011250142628";
    $doc->suframa = null;
    $azul->doc($doc);

    $ret = $azul->emitirAWB();

    echo json_encode($ret);
}

if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'rastrear') {
    $azul = new AzulCargo(1);
    $azul->awb = "63270589";
    $azul->chave = "";
    $ret = $azul->rastrear();
    
    echo json_encode($ret);
}

if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'unidade_proxima') {
    $azul = new AzulCargo(1);
    $azul->cep = '74705270';
    $ret = $azul->unidade_proxima();

    echo json_encode($ret);
}

if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'consultar_faturas') {
    $azul = new AzulCargo(1);
    $azul->cnpj = "";
    $azul->dataInicio = (new DateTime())->format('Y-m-d');
    $azul->dataFinal = (new DateTime())->format('Y-m-d');
    $ret = $azul->consultar_faturas();

    echo json_encode($ret);
}

if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'emitir_etiqueta') {
    $azul = new AzulCargo(1);
    $azul->awb = "52932305";
    $ret = $azul->emitir_etiqueta();

    echo json_encode($ret);
}

if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'download_xml') {
    $azul = new AzulCargo(1);
    $azul->awb = "52932305";
    $ret = $azul->download_xml();

    echo json_encode($ret);
}
