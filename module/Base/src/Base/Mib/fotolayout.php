<miniaturas>
    <miniaturas-secao>
    <div class="pagination pagination-centered">
        <ul>
         <li class="disabled"><a href="#">P&aacute;ginas: </a></li>
        <miniaturas-secao-coringa>
            <miniaturas-secao-selecionada>
                    <li class="active">
                        <a href="#"><!-- texto-secao-numero-selecionada --></a>
                    </li>
            </miniaturas-secao-selecionada>
            <miniaturas-secao-nao-selecionada>    
                    <li>
                        <a href="?p=<!-- texto-secao-numero-nao-selecionada -->/"><!-- texto-secao-numero-nao-selecionada --></a>
                    </li> 
            </miniaturas-secao-nao-selecionada>
                        
            </ul>
        </div>
            <p><span class="texto3">Clique nas fotos para ampliar!</span></p>
    </miniaturas-secao>
    <div id="galeriamodelo">
        <miniaturas-coringa>
            <miniaturas-foto-linha-inicio>

            </miniaturas-foto-linha-inicio>
            <miniaturas-foto>
                <a href="?a=foto&n=<!-- miniatura-foto-numero -->/" title="Clique para Ampliar" name="Sigla<!-- miniatura-foto-numero -->"
                        id="Sigla<!-- miniatura-foto-numero -->">
                    <img width="<!-- miniatura-foto-largura-pequena -->" height="<!-- miniatura-foto-altura-pequena -->" src="<!-- miniatura-foto-diretorio-pequena -->" name="fSigla<!-- miniatura-foto-numero -->" id="fSigla<!-- miniatura-foto-numero -->" alt="Foto número <!-- miniatura-foto-numero -->" data-image="<!-- ampliada-foto-diretorio-grande -->" />
                </a>
            </miniaturas-foto>
            <miniaturas-foto-linha-final>
            
            </miniaturas-foto-linha-final>
    </div>
</miniaturas>
<ampliadas>
                
            <div class="pure-g">
                <ampliadas-anterior>
			        <div class="pure-u-1-3">
                        <a href="?a=foto&n=<!-- ampliada-foto-controle-numero-anterior -->/" class="pure-button pure-button-primary"><i class="fa fa-step-backward" aria-hidden="true"></i> Ver Anterior</a>
                    </div>
                </ampliadas-anterior>
			        <div class="pure-u-1-3">
                        <a href="?p=<!-- secao-numero -->" class="pure-button pure-button-primary"><i class="fa fa-arrow-up" aria-hidden="true"></i> Ver Todas</a>
                    </div>
                    
                <ampliadas-proxima>
			        <div class="pure-u-1-3">
                        <a href="?a=foto&n=<!-- ampliada-foto-controle-numero-proximo -->/" class="pure-button pure-button-primary"><i class="fa fa-step-forward" aria-hidden="true"></i> Ver Próxima</a>
                    </div>
                </ampliadas-proxima>
            </div>
            <p>
                    [Foto número
                    <!-- ampliada-foto-numero -->]
            </p>
            <input type="text" disabled="disabled" style="border:none; margin:auto; width:100%; height:<!-- ampliada-foto-altura-grande -->px; background:url(<!-- ampliada-foto-diretorio-grande -->) no-repeat center top; background-size: contain;" data-big-width="<!-- ampliada-foto-largura-grande -->" data-big-height="<!-- ampliada-foto-altura-grande -->"
                    title="<!-- ampliada-foto-titulo -->">
</ampliadas>