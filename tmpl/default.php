<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_custom
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;
//$locations = modPhocadownloadPanelHelper::getLocations();
$i = 1;
$statusArr = array(1=>'Inscrições Abertas',2=>'Em Andamento',3=>'Processos Finalizados');       
?>
<div class="phocadownload-panel<?php echo $moduleclass_sfx ?>" <?php if ($params->get('backgroundimage')): ?> style="background-image:url(<?php echo $params->get('backgroundimage'); ?>)"<?php endif; ?> >
    <?php if($locations){ ?>
        <ul class="nav nav-tabs nav-tabs-parent" id="myTab">
            <?php  foreach($locations as $location){?>
                <li class="<?php echo ($i === 1)?'active':'';?>"><!--ABAS DAS LOCALIDADES-->
                    <a data-toggle="tab" href="#tabs-h-<?php echo $location->id;?>" class="nav-tabs-inner">
                        <span class="nav-tabs-text"><?php echo strtoupper($location->title);?></span>
                    </a>
                </li><!--FIM ABAS DAS LOCALIDADES-->
                <?php  $i++;?>
            <?php  }?>
        </ul>

        <?php $i = 1; ?>
        <div class="tab-content">
            <?php foreach($locations as $location){?>
                <div class="tab-pane <?php echo ($i === 1)?'active':'';?>" id="tabs-h-<?php echo $location->id;?>"><!--CONTEUDO DAS LOCALIDADES-->

                    <?php
                    $counter = array();
                    for($i_status=1;$i_status<=3;$i_status++ ){
                        foreach($location->units as $value){
                            if((int)$value->STATUS_FILE === $i_status){$counter[$i_status]++;}
                        }
                    }
                    ?>

                    <div class="tabbable tabs-left">
                        <ul class="nav nav-tabs nav-tabs-children"><!--ABAS DOS STATUS-->
                            <li class="active">
                                <a data-toggle="tab" href="#tabs-v-<?php echo $location->id; ?>-1">INSCRIÇÕES ABERTAS <span class="badge"><?php echo $counter[1];?></span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#tabs-v-<?php echo $location->id; ?>-2">EM ANDAMENTO <span class="badge"><?php echo $counter[2];?></span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#tabs-v-<?php echo $location->id; ?>-3">PROCESSO FINALIZADO <span class="badge"><?php echo $counter[3];?></span></a>
                            </li>
                        </ul><!--FIM ABAS DOS STATUS-->
                        <div class="tab-content"><!--CONTEUDO ABAS DOS STATUS-->
                            <?php for($i_status=1;$i_status<=3;$i_status++ ){?>
                                <?php $padding = ($counter[$i_status] >= 6)?true:false; ?>
                                    <div id="tabs-v-<?php echo $location->id; ?>-<?php echo $i_status?>" class="tab-pane <?php if($i_status == 1)echo 'active';?>">
                                        
										<?php /*?>
										<div>
                                            <table class="table table-striped table-hover" style="margin-bottom: -1px;">
                                                <thead>
                                                    <th style="text-align: center;background-color: #FFF;width: 5%;">#</th>
                                                    <th style="text-align: left;  background-color: #FFF;width: 30%;">SELEÇÃO</th>
                                                    <th style="text-align: center;background-color: #FFF;width: 15%;<?php //if($padding) echo 'padding-right: 6%;';?>">Nº</th>
                                                    <th style="text-align: center;background-color: #FFF;width: 15%;<?php if($padding) echo 'padding-right: 6%;';?>">UNIDADE</th>
                                                    <th style="text-align: center;background-color: #FFF;width: 15%;<?php if($padding) echo 'padding-right: 6%;';?>">TIPO</th>
                                                    <th style="text-align: center;background-color: #FFF;width: 20%;<?php if($padding) echo 'padding-right: 6%;';?>">SITUAÇÃO</th>
                                                </thead>
                                            </table>
                                        </div>
										
										<div class="<?php echo ($padding === true)?'table-scrool':'table-unscrool'?>" style="overflow-y: unset;">
										<?php */?>
										
										<div class="table-scrool" style="overflow-y: unset;height: 335px;">
                                            
                                            <?php if(!empty($location->units)){ ?>
                                                <table class="table table-striped table-hover" style="margin-bottom: 4px;">
													<thead>
														<th style="text-align: center;background-color: #FFF;width: 5%;">#</th>
														<th style="text-align: left;  background-color: #FFF;width: 30%;">SELEÇÃO</th>
														<th style="text-align: center;background-color: #FFF;width: 15%;">Nº</th>
														<th style="text-align: center;background-color: #FFF;width: 15%;">UNIDADE</th>
														<!--<th style="text-align: center;background-color: #FFF;width: 15%;<?php //if($padding) echo 'padding-right: 6%;';?>">TIPO</th>-->
														<th style="text-align: center;background-color: #FFF;width: 20%;">SITUAÇÃO</th>
													</thead>

                                                    <tbody>
                                                        <?php $x = 1;?>
														<?php $check = 0;?>
                                                        <?php foreach ($location->units as $l){?>
                                                            <?php $type =  ($l->CAT_TYPE == "I")?'Interno':'Externo';?>
                                                            <?php list($selecao, $num_processo) = explode("::", $l->CAT_TITLE); ?>
                                                            <?php
                                                                foreach (array('isgh'=>'info','hgwa'=>'success','hrc'=>'success','hrn'=>'success','upa'=>'danger','aps'=>'warning') as $k => $v){
                                                                    if(strpos(strtolower($l->CAT_UNIT_TITLE), (string)$k)> -1){
                                                                        $class=$v;
                                                                    }
                                                                }
                                                            ?>
                                                            <?php
                                                                $lastFile = utf8_strtolower($l->LAST_FILE);
                                                                $lastFile = ucwords($lastFile);
                                                                ?>
                                                        
                                                            <?php if($l->STATUS_FILE == $i_status){ ?>
																<?php $check++; ?>
                                                                <tr>
                                                                    <td style="width: 5%;vertical-align: middle;" align="center"><?php echo $x++; ?></td>
                                                                    <td style="width: 32%;vertical-align: middle;"><a href="/processos_seletivos/index.php?option=com_phocadownload&view=category&id=<?php echo $l->CAT_ID; ?>"><?php echo $selecao; ?></a></td>
                                                                    <td style="width: 15%;vertical-align: middle;" align="center"><?php echo $num_processo; ?></td>
                                                                    <td style="width: 15%;vertical-align: middle;" align="left"><span class="flag flag-<?php echo $class;?>"><?php echo strtoupper($l->CAT_UNIT_TITLE); ?></span></td>
                                                                    <!--<td style="width: 15%;vertical-align: middle;" align="left"><?php //echo $type; ?></td>-->
                                                                    <td style="width: 20%;vertical-align: middle;" align="center"><?php echo $lastFile; ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
												<?php if($check == 0){ ?>
                                                    <div class="jumbotron jumbotron-center" style="margin-bottom: 0;background-color: transparent;border-radius: 0;">
                                                        <h1><span class="glyphicon glyphicon-th-list"></span></h1>
                                                        <p>Não há processos com status <?php echo $statusArr[$i_status]; ?> para exibir em <?php echo $location->title; ?></p>
                                                    </div>
                                                <?php } ?>
                                            <?php }else{ ?>
                                                <div class="jumbotron jumbotron-center" style="margin-bottom: 0;background-color: transparent;border-radius: 0;">
                                                    <h1><span class="glyphicon glyphicon-th-list"></span></h1>
                                                    <p>Não há processos com status <?php echo $statusArr[$i_status]; ?> para exibir em <?php echo $location->title; ?></p>
                                                </div>
                                            <?php } ?>
                                            
                                            
                                        </div>
                                    </div>
                            <?php }?>
                        </div><!--FIM CONTEUDO ABAS DOS STATUS-->
                    </div>
                </div><!--FIM CONTEUDO DAS LOCALIDADES-->
            <?php $i++;?>
            <?php }?>
        </div>
    <?php }else{ ?>
        <div class="jumbotron jumbotron-center" style="margin-bottom: 0;background-color: transparent; border-radius: 0;">
            <h1><span class="glyphicon glyphicon-th-list"></span></h1>
            <p>Não há Processos Seletivos para exibir</p>
        </div>
    <?php } ?>
</div>