<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Authentication\Storage\Session as SessionStorage;

use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Base\Upload\UploadHandler,
    Base\Mib\URL,
    Base\Mib\Numeros,
    Base\Mib\Noticia,
    Base\Mib\Imagem;

class ConfigurationController extends AbstractActionController
{
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function noticiasdestaquesAction()
    {
        $noticias = $this->getEm()->getRepository("Base\Entity\Noticia")->findBy(array('ativo' => 1), array('dt' => 'DESC', 'hr' => 'DESC'), 6, 0);
        if (!empty($noticias))
            $noticias = Noticia::listarDestaques($noticias);
        echo json_encode([
            'noticias' => $noticias
        ]);
        die();
    }

    public function buscaenderecosAction()
    {
        $busca = $this->params()->fromRoute("busca", "");
        $enderecos = [];
        $enderecoRepository = $this->getEm()->getRepository("Admin\Entity\AddressSearchs");
        if (Numeros::pegCEP($busca) === false) {
            $sql = "SELECT * FROM address_searchs WHERE address like '%" . $busca . "%' limit 0, 6";
            $rsm = new ResultSetMappingBuilder($this->getEm());
            $rsm->addRootEntityFromClassMetadata("Admin\Entity\AddressSearchs", 'noticia');
            $query = $this->getEm()->createNativeQuery($sql, $rsm);
            $busca = $query->getResult();
        } else {
            $busca = $enderecoRepository->findByPostalCode($busca);
        }
        if (!empty($busca)) {

            foreach ($busca as $endereco) {
                $enderecos[] = [
                    "pais" => "Brasil",
                    "estado" => $endereco->getCity()->getState()->getInitials(),
                    "cidade" => $endereco->getCity()->getName(),
                    "bairro" => $endereco->getDistrict()->getName(),
                    "endereco" => $endereco->getAddress(),
                    "cep" => $endereco->getPostalCode()
                ];
            }
        }
        echo json_encode($enderecos);
        die();
    }

    public function uploadAction()
    {
        $sessao = new SessionStorage("Admin");

        $uploader = new UploadHandler();

        // Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $uploader->allowedExtensions = array("jpeg", "jpg", "png", "gif"); // all files types allowed by default

        // Specify max file size in bytes.
        $uploader->sizeLimit = 4194304;

        // Specify the input name set in the javascript.
        $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default

        // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
        $dirUpload = URL::getInstance()->getRoot() . "img/";
        $uploader->chunksFolder = $dirUpload . "chunks/";
        $uploadFolder = $dirUpload . "fotos/noticias/";
        $result = "";
        $request = $this->getRequest();
        if ($request->isPost()) {
            header("Content-Type: text/plain");

            // Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
            // For example: /myserver/handlers/endpoint.php?done
            if (isset($_GET["done"])) {
                $result = $uploader->combineChunks($uploadFolder);
            }
            // Handles upload requests
            else {
                // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
                $result = $uploader->handleUpload($uploadFolder);

                // To return a name used for uploaded file you can use the following line.
                $result["uploadName"] = $uploader->getUploadName($uploadFolder);
            }
        } else if ($request->isDelete()) {
            $result = $uploader->handleDelete($uploadFolder);
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
            $result =  "Erro 405 - Method Not Allowed";
        }
        echo json_encode($result);
        die();
    }

    public function imagensnoticiaAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $prefixo = $this->params()->fromRoute('prefixo', 0);
        $imagens = [];
        foreach (Noticia::getImagens($prefixo . "-" . $id) as $img) {
            $nome = Imagem::getBaseName($img);
            $uuid = Imagem::getUUID($img);
            $imagens[] = (object) [
                'name' => $nome,
                'uuid' => $uuid,
                'thumbnailUrl' => "/" . Noticia::$imagensDir . $nome
            ];
        }

        echo json_encode($imagens);
        die();
    }

    public function previewlinkAction()
    {

        $url = $_REQUEST['url'];
        $url = $this->checkValues($url);
        $parseurl = parse_url($url);
        if (empty($parseurl["scheme"])) {
            $url = "http://$url";
            $parseurl = parse_url($url);
        }

        $string = $this->fetch_record($url);

        /// fecth title
        $title_regex = "/<title>(.+)<\/title>/i";
        preg_match_all($title_regex, $string, $title, PREG_PATTERN_ORDER);
        $url_title = $title[1];

        /// fecth decription
        // $tags = get_meta_tags($url);
        $tags = $this->getMetaTags($string);

        // fetch images
        $image_regex = '/<img[^>]*' . 'src=[\"|\'](.*)[\"|\']/Ui';
        preg_match_all($image_regex, $string, $img, PREG_PATTERN_ORDER);
        $images_array = $img[1];

        $host = $parseurl['host'];
        $title = @$tags['og:title'];
        $title = (empty($title)) ? @$url_title[0] : $title;
        $description = @$tags['description'];

        $k = 1;
        $x = sizeof($images_array);
        $i = 0;
        $images = [];
        while ($i < $x && $k < 4) {
            if (@$images_array[$i]) {
                $imagem = preg_replace('/^\//', "", @$images_array[$i]);
                $imagemCompleta = $parseurl["scheme"] . "://" . $host . "/" . $imagem;
                $info = $this->trataImagem($imagem);
                if (!is_array($info))
                    $info = $this->trataImagem($imagemCompleta);
                if (is_array($info)) {
                    list($width, $height, $type, $attr) = $info;
                    if ($width >= 50 && $height >= 50) {
                        $images[] = $info["imagem"];
                        $k++;
                    }
                }
            }
            $i++;
        }
        $result = [
            "titulo" => $title,
            "descricao" => $description,
            "url" => $url,
            "host" => $host,
            "imagens" => $images
        ];
        echo json_encode($result);
        die();
    }

    public function checkloginAction()
    {
        $result['login'] = $this->validaLogin();
        echo json_encode($result);
        die();
    }

    private function validaLogin()
    {
        $usuarioIdentidade = $this->getServiceLocator()->get('ViewHelperManager')->get('UsuarioIdentidade');
        $usuario = $usuarioIdentidade("Admin");
        if ($usuario)
            return true;
        return false;
    }

    private function getMetaTags($str)
    {
        $pattern = '
    ~<\s*meta\s

    # using lookahead to capture type to $1
        (?=[^>]*?
        \b(?:name|property|http-equiv)\s*=\s*
        (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
        ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
    )

    # capture content to $2
    [^>]*?\bcontent\s*=\s*
        (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
        ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
    [^>]*>

    ~ix';

        if (preg_match_all($pattern, $str, $out))
            return array_combine($out[1], $out[2]);
        return array();
    }

    private function checkValues($value)
    {
        $value = trim($value);
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }
        $value = strtr($value, array_flip(get_html_translation_table(HTML_ENTITIES)));
        $value = strip_tags($value);
        $value = htmlspecialchars($value);
        return $value;
    }

    private function fetch_record($path)
    {
        $file = fopen($path, "r");
        if (!$file) {
            exit("Problem occured");
        }
        $data = '';
        while (!feof($file)) {
            $data .= fgets($file, 1024);
        }
        return $data;
    }

    private function trataImagem($url)
    {
        $info = @getimagesize($url);
        if (is_array($info)) {
            $info["imagem"] = $url;
        }
        return $info;
    }

    /**
     *
     * @return ServiceLocatorInterface
     */

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     *
     * @return EntityManager
     */
    public function getEm()
    {
        if (null === $this->em)
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        return $this->em;
    }


    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;
    protected $em;
}
