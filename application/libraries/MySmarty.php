<?php
/*!
 * テンプレートエンジンSmartyを提供するクラス
 *
 * Smarty本体はcomposerでインストールされ "vendor/smarty/smarty/" にあります。
 * smarty/libs/Smarty.class.php は config.php の composer_autoload によって
 * 既に利用できる状態になっている前提です。
 *
 * もし、このファイルでエラーになる場合は、以下を試してみて下さい。
 * > cd application
 * > php ../composer.phar update
 * > php ../composer.phar install
 *
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

//require_once FCPATH. '/vendor/smarty/smarty/libs/Smarty.class.php';

class MySmarty extends Smarty
{
    public function __construct()
    {
		parent::__construct();

        $this->compile_dir = APPPATH . "views/templates_c";
        $this->template_dir = APPPATH . "views/templates";
        $this->assign('APPPATH', APPPATH);
        $this->assign('BASEPATH', BASEPATH);

        // Assign CodeIgniter object by reference to CI
        if (method_exists($this, 'assignByRef')) {
			$ci =& get_instance();
			$this->assignByRef("ci", $ci);
		}
        log_message('debug', "Smarty Class Initialized");
    }

	/*
	 * ヘッダー、フッターを付けない
	 */
    function execute($template, $data = array(), $return = FALSE)
	{
		$data['BASE_URL'] = base_url(). "/";
		$data['SITE_URL'] = site_url(). "/";
		$data['CURRENT_URL'] = current_url();
		$data['INDEX_PAGE'] = index_page();
        foreach ($data as $key => $val) {
			$this->assign($key, $val);
		}

        if ($return == FALSE) {
			$CI =& get_instance();
			if (method_exists($CI->output, 'set_output')) {
				$CI->output->set_output($this->fetch($template));
			} else {
				$CI->output->final_output = $this->fetch($template);
			}
			return "";
		} else {
			return $this->fetch($template);
		}
		return "";
	}

    function view($template, $data = array(), $return = FALSE)
    {
		$data['INCLUDE_PAGE'] = $template;
		return $this->execute("page_layout.tpl", $data, $return);
    }

}
?>
