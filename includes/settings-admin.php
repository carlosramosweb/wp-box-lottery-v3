<?php

/**
 *
 * @link              https://criacaocriativa.com
 * @since             1.0.0
 * @package           WP_Box_Lottery_v3
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

$message = "";
$tab = (isset($_GET['tab'])) ? trim($_GET['tab']) : '';

if( isset( $_POST['_update'] ) && isset( $_POST['_wpnonce'] ) ) {
	$_update 	= sanitize_text_field( $_POST['_update'] );
	$_wpnonce 	= sanitize_text_field( $_POST['_wpnonce'] );
}

if(isset($_wpnonce) && isset($_update)) {
	if ( ! wp_verify_nonce( $_wpnonce, "wp-box-lottery-v3-settings" ) ) {
		$message = 'error';
		
	} else if (empty($_update)) {
		$message = 'error';			
	}

	if( isset( $_POST['settings'] ) ) {
		$post_settings = array();
		$post_settings = (array)$_POST['settings'];

		if (empty($tab)) {
			$new_settings['enabled'] = ( isset( $post_settings['enabled'] ) ) ? sanitize_text_field( $post_settings['enabled'] ) : 'no';
            $new_settings['key'] = ( isset( $post_settings['key'] ) ) ? sanitize_text_field( $post_settings['key'] ) : '';
		}
		update_option( "wp_box_lottery_v3_settings", $new_settings );
	}
	$message = "updated";
}

$settings = array();
$settings = get_option( 'wp_box_lottery_v3_settings' );

$enabled = esc_attr($settings['enabled']);
$key = esc_attr($settings['key']);
?>

<div id="wpwrap">

	<h1>Loterias da Caixa Economica Federal</h1>
	<p>Esse plugin exibe os resultados da Loteria Federal da Caixa Economica Federal.<br/> Usando os <strong>Shortcodes</strong> você poderá <strong>criar layouts</strong> organizado para seus internautas <strong>visualizar os resultados</strong> de cada sorteio.</p>

    <?php if(isset($message)) { ?>
        <div class="wrap">
    	<?php if($message == "updated") { ?>
            <div id="message" class="updated notice is-dismissible" style="margin-left: 0px;">
                <p>Atualizações feita com sucesso!</p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">
                        Fechar
                    </span>
                </button>
            </div>
            <?php } ?>
            <?php if($message == "error") { ?>
            <div id="message" class="updated error is-dismissible" style="margin-left: 0px;">
                <p>Opa! Não conseguimos fazer as atualizações!</p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">
                        Fechar
                    </span>
                </button>
            </div>
        <?php } ?>
    	</div>
    <?php } ?>

    <div class="wrap woocommerce">
        <nav class="nav-tab-wrapper wc-nav-tab-wrapper">
       		<a href="<?php echo esc_url( admin_url( 'admin.php?page=settings-box-lottery-v3' ) ); ?>" class="nav-tab <?php if( $tab == "" ) { echo "nav-tab-active"; }; ?>">Configurações</a>
       		<a href="<?php echo esc_url( admin_url( 'admin.php?page=settings-box-lottery-v3&tab=shortcodes' ) ); ?>" class="nav-tab <?php if( $tab == "shortcodes" ) { echo "nav-tab-active"; }; ?>">Shortcodes</a>
        </nav>
    </div>

    <?php if(empty($tab)) { ?>
	<form action="<?php echo esc_url( admin_url( 'admin.php?page=settings-box-lottery-v3' ) ); ?>" method="post" enctype="application/x-www-form-urlencoded">
        <!---->
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label>
                            Habilitar/Desabilitar
                        </label>
                    </th>
                    <td>
                    	&nbsp;&nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="checkbox" name="settings[enabled]" value="yes" <?php if( $enabled == "yes" ) { echo 'checked="checked"'; } ?> class="form-control">
                            Deixa marcado para habilitar o plugin.
                        </label>
                   </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label>
                            Chave API
                        </label>
                    </th>
                    <td>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="text" name="settings[key]" class="form-control" value="<?php echo $key; ?>">
                            Preencha com a Chave da API. 
                            <a href="https://apiloterias.com.br/" title="Obter Token da API" alt="Link para Obter Token da API" target="_blank">Obter Token?</a>
                        </label>
                   </td>
                </tr>
        	</tbody>
        </table>
        <div class="submit">
            <button class="button button-primary" type="submit">
            	Salvar Alterações
            </button>
            <input type="hidden" name="_update" value="1">
            <input type="hidden" name="_wpnonce" value="<?php echo sanitize_text_field( wp_create_nonce( 'wp-box-lottery-v3-settings' ) ); ?>">
            <!---->
            <span>
            	<span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: sub;"></span>
				Não esqueça de <strong>salvar suas alterações</strong>.
            </span>
        </div>
    </form>
    <?php } ?>

    <?php if(isset($tab) && $tab == 'shortcodes') { ?>
	<h3>Lista com todos os Shortcodes do plugin</h3>
	<p>Para funcionar a <strong>exibição dos resultados da loteria</strong> precisa inserir os shortcodes na sua página ou layout.<br/> <strong>Copie e Cole</strong> o <strong>Shortcode desejado</strong> e inserir no seu layout.</p>

    <h3>Lista de Loterias (lottery)</h3>
    <span class="item-lottery">
        megasena
    </span>
    <span class="item-lottery">
        quina
    </span>
    <span class="item-lottery">
        lotofacil
    </span>
    <span class="item-lottery">
        lotomania
    </span>
    <span class="item-lottery">
        timemania
    </span>
    <span class="item-lottery">
        diadesorte
    </span>
    <span class="item-lottery">
        federal
    </span>
    <span class="item-lottery">
        loteca
    </span>
    <span class="item-lottery">
        supersete
    </span>
    <br/>

    <h3>Lista de Jogos (game)</h3>
    <span class="item-game">
        2400 ou Last (Último Automaticamente)
    </span>

    <h3>Modelos de Shortcodes</h3>
	<div class="container">
	  <div class="row">
	    <div class="box-shortcode">
	      <strong>[wp_box_lottery_v3 lottery='megasena' game='2400']</strong>
	    </div>
	  </div>
      <div class="row">
        <div class="box-shortcode">
          <strong>[wp_box_lottery_v3 lottery='lotomania' game='last']</strong>
        </div>
      </div>
	</div>

    <?php } ?>

</div>

<style type="text/css">
.item-lottery {
    background: #ccc; 
    margin: 0 3px 0 0; 
    padding: 5px 15px; 
    border-radius: 3px; 
    display: inline-block;
}
.item-game {
    background: #cce; 
    margin: 0 3px 0 0; 
    padding: 5px 15px; 
    border-radius: 3px; 
    display: inline-block;
}
.box-shortcode {
    background: #ccc; 
    margin: 3px; 
    padding: 10px; 
    border-radius: 3px;
}
</style>