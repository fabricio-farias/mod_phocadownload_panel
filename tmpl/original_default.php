<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_custom
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;
$list = modPhocadownloadPanelHelper::getList($params);

for($i=1;$i<=3;$i++ ){
    foreach($list as $value){
        if($value->STATUS_FILE == $i) $counter[$i]++;
    }
}
?>

<div class="phocadownload-panel<?php echo $moduleclass_sfx ?>" <?php if ($params->get('backgroundimage')): ?> style="background-image:url(<?php echo $params->get('backgroundimage'); ?>)"<?php endif; ?> >
    <div data-tabs="2014">
        <div class="sprocket-tabs layout-top animation-slideandfade">
            <ul class="sprocket-tabs-nav">
                <li data-tabs-navigation="" class="active">
                    <span class="sprocket-tabs-inner">
                        <span class="sprocket-tabs-text">INSCRIÇÕES ABERTAS <span class="badge"><?php echo $counter[1];?></span></span>
                    </span>
                </li>
                <li data-tabs-navigation="" class="">
                    <span class="sprocket-tabs-inner">
                        <span class="sprocket-tabs-text">EM ANDAMENTO <span class="badge"><?php echo $counter[2];?></span></span>
                    </span>
                </li>
                <li data-tabs-navigation="" class="">
                    <span class="sprocket-tabs-inner">
                        <span class="sprocket-tabs-text">INSCRIÇÕES ENCERRADAS <span class="badge"><?php echo $counter[3];?></span></span>
                    </span>
                </li>
            </ul>
            <div class="sprocket-tabs-panels">
                <?php for($i=1;$i<=3;$i++ ){?>
                    <?php $padding = ($counter[$i] >= 6)?true:false; ?>
                <div data-tabs-panel="" class="sprocket-tabs-panel <?php if($i == 1)echo 'active';?>">
                    <div>
                        <table class="table table-striped table-hover" style="margin-bottom: -1px;">
                            <thead>
                                <th style="text-align: center;background-color: #FFF;width: 5%;">#</th>
                                <th style="text-align: left;  background-color: #FFF;width: 50%;">SELEÇÃO</th>
                                <th style="text-align: center;background-color: #FFF;width: 15%;<?php if($padding) echo 'padding-right: 4%;';?>">Nº PROCESSO</th>
                                <th style="text-align: center;background-color: #FFF;width: 15%;<?php if($padding) echo 'padding-right: 4%;';?>">UNIDADE</th>
                                <th style="text-align: center;background-color: #FFF;width: 15%;<?php if($padding) echo 'padding-right: 4%;';?>">DATA</th>
                            </thead>
                        </table>
                    </div>
                    <div class="<?php echo ($padding === true)?'table-scrool':'table-unscrool'?>">
                        <table class="table table-striped table-hover">
                            <tbody>
                                <?php $x=1;?>
                                <?php foreach ($list as $l){?>
                                    <?php list($selecao, $num_processo) = explode("::", $l->CAT_TITLE); ?>
                                    <?php
                                        switch ($l->CAT_UNIT){
                                            case 'isgh':
                                                $class="info";
                                            break;
                                            case 'hgwa':
                                                $class="success";
                                            break;
                                            case 'hrn':
                                                $class="success";
                                            break;
                                            case 'hrc':
                                                $class="success";
                                            break;
                                            case 'upa':
                                                $class="danger";
                                            break;
                                            case 'aps':
                                                $class="warning";
                                            break;
                                            default:
                                                $class="default";
                                            break;
                                        }
                                    ?>
                                    <?php if($l->STATUS_FILE == $i){ ?>
                                        <tr class="<?php //echo ($class == 'primary')?'info':$class;?>">
                                            <td style="width: 5%;" align="center"><?php echo $x++; ?></td>
                                            <td style="width: 50%;"><a href="index.php?option=com_phocadownload&view=category&id=<?php echo $l->CAT_ID; ?>"><?php echo $selecao; ?></a></td>
                                            <td style="width: 15%;" align="center"><?php echo $num_processo; ?></td>
                                            <td style="width: 15%;" align="center"><span class="flag flag-<?php echo $class;?>"><?php echo strtoupper($l->CAT_UNIT); ?></span></td>
                                            <td style="width: 15%;" align="center"><?php echo $l->YEAR_FILE; ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>