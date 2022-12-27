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

$name       = (isset($results['nome'])) ? $this->get_class_name_lottery($results['nome']) : '';
$title      = (isset($results['nome'])) ? esc_attr($results['nome']) : 'N/A';
$lottery    = (isset($results['numero_concurso'])) ? esc_attr($results['numero_concurso']) : 0;
$date       = (isset($results['data_concurso'])) ? esc_attr($results['data_concurso']) : 'N/A';
$local      = (isset($results['local_realizacao'])) ? esc_attr($results['local_realizacao']) : 'N/A';

$dozens     = (isset($results['dezenas'])) ? $results['dezenas'] : 'N/A';
$awards     = (isset($results['premiacao'])) ? $results['premiacao'] : 'N/A';

$premium    = (isset($awards)) ? $this->get_total_premium($awards) : 'R$ 0';

$accumulated        = (isset($results['arrecadacao_total'])) ? esc_attr($results['arrecadacao_total']) : 0;
$next_accumulated   = (isset($results['valor_estimado_proximo_concurso'])) ? esc_attr($results['valor_estimado_proximo_concurso']) : 'R$ 0';
$next_date_lottery  = (isset($results['data_proximo_concurso'])) ? esc_attr($results['data_proximo_concurso']) : '';
$next_lottery       = (isset($results['concurso_proximo'])) ? esc_attr($results['concurso_proximo']) : '';
$special_lottery    = (isset($results['nome_acumulado_especial'])) ? esc_attr($results['nome_acumulado_especial']) : 'N/A';
?>

<link rel="stylesheet" href="<?php echo $plugin_dir_url; ?>assets/css/style.css" media="all">

<section class="lottery-totem lot-<?php echo $name; ?>">
   <header class="lottery-totem__header">
      <div class="lottery-totem__header-grid">
         <div class="lottery-totem__header-grid__result">
            <div class="result__title">
               <h2><?php echo $title; ?></h2>
            </div>
            <div class="result__draw-date">
                Sorteio: <strong><?php echo date('d/m/Y', strtotime($date)); ?></strong>
            </div>
            <div class="result__draw">
                Concurso: <strong><?php echo $lottery; ?></strong>
            </div>
            <div class="result__local">
               <div class="text-center text-sm-left">
                <span>Local de Sorteio:</span><br>
                <strong><?php echo $local; ?></strong>
            </div>
            </div>
            <div class="result__prize">
                <div class="text-left">Valor do prêmio</div>
                <div class="result__prize__wrap">
                    <span>R$</span> 
                    <span class="result__prize__value"><?php echo str_replace('R$ ', '', $premium); ?></span>
                </div>
            </div>
         </div>
         <div></div>
      </div>
   </header>
   <div class="lottery-totem__modules-grid">
      <div class="lottery-totem__body">
         <div class="lottery-totem__body__content card">
            <div class="result__content__wrap result__content__wrap--tens">
               <div class="result__tens-grid">
                <?php if (isset($dozens)) { ?>
                    <?php foreach ($dozens as $key => $row) { ?>
                  <div class="lot-bg-light"><span><?php echo $row; ?></span></div>
                    <?php } ?>
                <?php } ?>
               </div>
                <?php if ($accumulated) { ?>
                <p class="text-center text-uppercase color-primary">                    
                    <strong>Acumulou!</strong>
                </p>
                <?php } ?>
            </div>
            <table class="result__table-prize">
               <tbody>
                <?php if ($name=='federal') { ?>
                  <tr>
                    <td class="text-center">
                        <strong>Premiação</strong>
                    </td>
                    <td class="text-center">
                        <strong>Bilhete</strong>
                    </td>
                    <td class="text-center">
                        <strong>Prêmio</strong>
                    </td>
                  </tr>
                <?php } else { ?>
                  <tr>
                    <td class="text-center">
                        <strong>Premiação</strong>
                    </td>
                    <td class="text-center">
                        <strong>Ganhadores</strong>
                    </td>
                    <td class="text-center">
                        <strong>Prêmio</strong>
                    </td>
                  </tr>
                <?php } ?>
                    <?php if (isset($awards)) { ?>
                        <?php foreach ($awards as $key => $row) { ?>
                          <?php if ($name=='loteria-federal') { ?>
                          <tr>
                              <td class="text-center"><?php echo $row['nome']; ?></td>
                              <td class="text-center"><?php echo $row['bilhete']; ?></td>
                              <td class="text-center">
                                  R$ <?php echo number_format($row['valor_total'], 2, ',', '.'); ?>
                              </td>
                          </tr>
                          <?php } else { ?>
                          <tr>
                              <td class="text-center"><?php echo $row['acertos']; ?></td>
                              <td class="text-center"><?php echo $row['quantidade_ganhadores']; ?></td>
                              <td class="text-center">
                                  R$ <?php echo number_format($row['valor_total'], 2, ',', '.'); ?>
                              </td>
                          </tr>
                          <?php } ?>
                        <?php } ?>
                    <?php } ?>
               </tbody>
            </table>
         </div>
      </div>
      <div class="lottery-totem__aside">
         <div class="lottery-totem__aside__wrap">
            <div class="lottery-totem__nextdraw">
               <div class="lottery-totem__nextdraw__title">Próximo Concurso</div>
               <div class="card mt-0">
                  <div class="lottery-totem__nextdraw__block card">
                    <div class="lottery-totem__nextdraw__info">
                        <div class="lottery-totem__nextdraw__prize">
                            <div class="lottery-totem__nextdraw__prize__wrap">
                                <span>R$</span> 
                                <span class="lottery-totem__nextdraw__prize__value">
                                    <?php 
                                    $price_accumulated = str_replace('R$ ', '', $next_accumulated);
                                    echo number_format($price_accumulated, 2, ',', '.'); ?>
                                </span>
                            </div>
                        </div>
                        <?php if ($accumulated > 0) { ?>
                        <div class="lottery-totem__nextdraw__is-jackpot">
                            <span>Acumulada!</span>
                        </div>
                        <?php } ?>
                        <div class="lottery-totem__nextdraw__draw-date">
                            Sorteio: <strong><?php echo date('d/m/Y', strtotime($next_date_lottery)); ?></strong>
                        </div>
                        <div class="lottery-totem__nextdraw__draw d-none d-lg-block">
                            Concurso: <strong><?php echo $next_lottery; ?></strong>
                        </div>
                    </div>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>
</section>