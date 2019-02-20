<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<?php
if ($kpa_id > 0) {
    $kpaBaseId = $kpa['kpa_id'];
    $isReadOnly = empty($isReadOnly) ? 0 : 1;
    $readOnlyText = $isReadOnly ? 'readonly="readonly"' : "";
    $disabledText = $isReadOnly ? 'disabled="disabled"' : "";
    $numToAlph = array(1 => "a", 2 => "b", 3 => "c", 4 => "d",5 => "e",6 => "f",7 => "g",8 => "h",9 => "i",10 => "j",11 => "k",12 => "l",13 => "m",14 => "n",15 => "o",16 => "p");
    $cq_no_inKpa = 0;
    $kpa_count = count($kqs);
    $isDisplay = (isset($diagnostic_type) && $diagnostic_type == 1) ? 'none' : 'block';
    $rating1 = $diagnosticLabels['Always'];
    $rating2 = $diagnosticLabels['Mostly'];
    $rating3 = $diagnosticLabels['Sometimes'];
    $numericRatingKpa =  $kpa['numericRating'];
    if (isset($diagnostic_type) && $diagnostic_type == 1) {
        $rating1=$diagnosticLabels['Level1'];
        $rating2=$diagnosticLabels['Level2'];
        $rating3=$diagnosticLabels['Level3'];
        $numericRatingKpa = 0;
        if( $kpa['rating']){
            $numericRatingKpa =  $kpa['numericRating'];
            $numericRatingKpa++;
        }

    }
    ?>
    <div role="tabpanel" data-tabtype="kpa" data-schemeid="<?php echo $scheme_id ?>" data-id="<?php echo $kpa_id; ?>" class="tab-pane fade in kpa<?php echo $isActive ? " active" : ""; ?>" id="kpa<?php echo $kpa_id; ?>">
        <div class="gradePart grade-kpa mr30">
            <?php if (isset($diagData['kpa_recommendations']) && $assessment['role'] == 4) { ?>
                    <a class='keyRecLink btn btn-primary execUrl kR kpa' id="kr_kpa_<?php echo $kpa_id; ?>" data-type='kpa' href="?controller=diagnostic&action=keyrecommendations&type=kpa&instance_id=<?php echo $kpa_id; ?>&assessment_id=<?php echo $assessment_id; ?>&assessor_id=<?php echo $assessor_id; ?>&lang_id=<?php echo $prefferedLanguage; ?>&external=<?php echo $external ?>&is_collaborative=<?php echo $is_collaborative ?>&kpa7id=<?php echo $kpa7Id; ?>&diagnostic_type=<?php echo $diagnostic_type; ?>">
                    <i class="fa "></i><?php echo $diagnosticLabels['Key_Recommendations']; ?></a>
                <?php } ?>
        </div>
        <h2 class="pad030 mb10"><?php 
            if (isset($image_name) && $image_name != '') {
                    ?>

                    <img src="<?php echo UPLOAD_URL_DIAGNOSTIC . '' . $image_name ?>" alt="<?php echo $image_name; ?>" id="resizable" class="resizable ui-widget-content">

                <?php }
             echo $kpa['kpa_name'];
            ?></h2>
        <div class="subTabWorkspaceOuter">
            <div class="ylwRibbonHldr" style=" display: <?php echo $isDisplay;?>">
                <a href="javascript:void(0);" class="navIcon collapsed" data-toggle="collapse" data-target="#tab2_Toggle" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
                <div class="collapse navbar-collapse tabitemsHldr" id="tab2_Toggle">
                    <ul class="yellowTab nav nav-tabs"> 
                        <?php
                        $i = 0;
                        //echo "<pre>"; print_r($kqs[9]);die;
                        foreach ($kqs[$kpa_id] as $kq_id => $kq) {
                            $i++;
                            $isKqKNComplete=true;
                            if(isset($assessment) && $assessment['role']==4 && $akqns!=0 && !empty($akqns)){
                            	if(isset($akqns[$kq_id]) && $akqns[$kq_id] && count($akqns[$kq_id])){
                            		foreach($akqns[$kq_id] as $akn_id=>$akn){
                            			if(empty($akn['text_data'])){
                            				$isKqKNComplete=false;
                            				$assessmentFilled=0;
                            				break;
                            			}
                            		}
                            	}
                            	else{
                            		$isKqKNComplete=false;
                            		$assessmentFilled=0;
                            	}
                            }else
                                $isKqKNComplete=false;
                            
                            if(!empty($kq['key_question_id'])) {
                            ?>
                            <li class="item<?php echo $i == 1 ? " active" : ""; ?> <?php echo $kq['numericRating'] > 0 && $isKqKNComplete ? "completed" : ""; ?>"><a href="#kq<?php echo $kq_id; ?>" data-toggle="tab" class="vtip" title="<?php echo htmlspecialchars($kq['key_question_text']); ?>"><?php echo $diagnosticLabels['Key_Question'] ." ". $i; ?></a>
                            	<?php if(isset($assessment) && $assessment['role']==4 && $akqns!=0){ ?><input name="aknotes[]" type="hidden" class='keyQ key-notes-val' id='keyQ_<?php echo $kq_id; ?>' value="<?php $isKqKNComplete ? print 1: print 0; ?>"><?php } ?>
                            </li>
                            <?php
                            }
                        }
                        ?>                    
                    </ul>
                </div>
            </div>
            <div class="subTabWorkspace pt10">
                <div class="tab-content">
                    <?php
                    $kq_no = 0;
                    foreach ($kqs[$kpa_id] as $kq_id => $kq) {
                        $kq_no++;
                        ?>
                        <div role="tabpanel" data-tabtype="keyQ" data-schemeid="<?php echo $scheme_id ?>" data-id="<?php echo $kq_id; ?>" class="tab-pane fade in keyQ<?php echo $kq_no == 1 ? " active" : ""; ?>" id="kq<?php echo $kq_id; ?>">
                            <div class="gradePart grade-keyQ">
                                <?php if(isset($diagData) && $diagData['kq_recommendations'] && $assessment['role'] == 4){?>
                                <a class='keyRecLink btn btn-primary execUrl kR keyQ' id="kr_keyQ_<?php echo $kq_id ?>" data-type='kq' href="?controller=diagnostic&action=keyrecommendations&type=key_question&instance_id=<?php echo $kq_id;?>&assessment_id=<?php echo $assessment_id; ?>&assessor_id=<?php echo $assessor_id;?>&lang_id=<?php echo $prefferedLanguage;?>&external=<?php echo $external?>&is_collaborative=<?php echo $is_collaborative?>" ><i class="fa "></i><?php echo !empty($kq['key_question_id'])?$diagnosticLabels['Key_Recommendations']:'';?></a>
                                    <?php } ?>
                            </div>
                           <?php if(!empty($kq['key_question_id'])){ ?>
                            <h3 class="mb10"><?php echo $kq_no; ?>. <?php echo $kq['key_question_text']; ?></h3>
                         <?php    } ?>
                            <div class="tab3Hldr">
                                <a href="javascript:void(0);" class="navIcon collapsed" data-toggle="collapse" data-target="#tab3_Toggle" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
                                <div class="collapse navbar-collapse tabitemsHldr" id="tab3_Toggle">
                                    <ul class="blackTab nav nav-tabs">
                                        <?php
                                        $i = 0;
                                        foreach ($cqs[$kq_id] as $cq_id => $cq) {
                                            $i++;
                                            $isCqKNComplete = true;
                                            if (isset($acqns) && isset($assessment) && $assessment['role'] == 4 && $acqns != 0) {
                                                if (isset($acqns[$cq_id]) && $acqns[$cq_id] && count($acqns[$cq_id])) {
                                                    foreach ($acqns[$cq_id] as $akn_id => $akn) {
                                                        if (empty($akn['text_data'])) {
                                                            $isCqKNComplete = false;
                                                            $assessmentFilled = 0;
                                                            break;
                                                        }
                                                    }
                                                } else {
                                                    $isCqKNComplete = false;
                                                    $assessmentFilled = 0;
                                                }
                                            } else
                                                $isCqKNComplete = false;
                                            ?>
                                        <?php if(!empty($kq['key_question_id'])){ ?>
                                            <li class="item<?php echo $i == 1 ? " active" : ""; ?> <?php echo $cq['numericRating'] > 0 && $isCqKNComplete ? "completed" : ""; ?>"><a href="#kqA-cq<?php echo $cq_id; ?>" data-toggle="tab" class="vtip" title="<?php echo htmlspecialchars($cq['core_question_text']); ?>"><?php echo $diagnosticLabels['Sub_Question'] ." ". $i; ?></a>
                                           <?php  if(isset($assessment) && $assessment['role']==4 && $acqns!=0){ ?> <input name="aknotes[]" type="hidden" class='coreQ key-notes-val' id='coreQ_<?php echo $cq_id; ?>' value="<?php $isCqKNComplete ? print 1: print 0; ?>"><?php } ?>
                                            </li>
                                            <?php
                                            }else{ ?>
                                            <span class="blankTabName">CORE STANDARDS</span>
                                                
                                           <?php }
                                        }
                                        ?>
                                    </ul>
                                    <ul class="ratInd flotedInTab">
                                        <li><span class="red"><?php echo $rating1;?></span></li>
                                        <li><span class="yellow"><?php echo $rating2;?></span></li>
                                        <li><span class="green"><?php echo $rating3;?></span></li>
                                        <?php if(!empty($kq['key_question_id'])){ ?>
                                            <li><span class="red"><?php echo $diagnosticLabels['Rarely'];?></span></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="panelWhitebox">
                                <div class="tab-content">
                                    <?php
                                    $cq_no = 0;

                                    foreach ($cqs[$kq_id] as $cq_id => $cq) {
                                        $cq_no++;
                                        $cq_no_inKpa++;
                                        if(!empty($cq['core_question_id'])) {
                                           $cq['numericRating'] = ''; 
                                        }
                                        ?>
                                        <div role="tabpanel" data-tabtype="coreQ" data-schemeid="<?php echo $scheme_id ?>" data-id="<?php echo $cq_id; ?>" class="tab-pane fade in coreQ<?php echo $cq_no == 1 ? " active" : ""; ?>" id="kqA-cq<?php echo $cq_id; ?>">

                                            <div class="gradePart grade-coreQ" style=" display: <?php echo $isDisplay;?>"><?php if(isset($diagData) && $diagData['cq_recommendations'] && $assessment['role'] == 4){?><a class='keyRecLink btn btn-primary execUrl kR coreQ' id="kr_coreQ_<?php echo $cq_id ?>"  data-type='cq' href="?controller=diagnostic&action=keyrecommendations&type=core_question&instance_id=<?php echo $cq_id;?>&assessment_id=<?php echo $assessment_id; ?>&assessor_id=<?php echo $assessor_id;?>&lang_id=<?php echo $prefferedLanguage;?>&external=<?php echo $external?>&is_collaborative=<?php echo $is_collaborative?>" ><i class="fa "></i><?php echo $diagnosticLabels['Key_Recommendations'];?></a><?php } ?> 
                                            </div>
                                            <?php if(!empty($cq['core_question_id'])) {?>
                                                <h4><?php echo $cq_no_inKpa; ?>. <span class="coreQText"><?php echo $cq['core_question_text']; ?></span></h4>
                                            <?php } ?>
                                            <ul class="questList">
                                                <?php
                                                $js_no = 0;                                                                                  echo '</pre>';
                                                foreach ($jss[$cq_id] as $js_id => $js) {
                                                    $js_no++;
                                                   $name = "data[$kpa_id-$kq_id-$cq_id-$js_id]";
                                                    if (empty($js['numericRating']))
                                                        $kpaFilled = 0;
                                                    
                                                    if($diagnostic_type == 1 && !empty($ratingLevelText)){
                                                        $rating1 = $ratingLevelText[$js_id][0]['translation_text'];
                                                        $rating2 = $ratingLevelText[$js_id][1]['translation_text'];
                                                        $rating3 = $ratingLevelText[$js_id][2]['translation_text'];
                                                        $rating11 = isset($ratingLevelText[$js_id][3]['translation_text'])?$ratingLevelText[$js_id][3]['translation_text']:'';
                                                        $rating12 = isset($ratingLevelText[$js_id][4]['translation_text'])?$ratingLevelText[$js_id][4]['translation_text']:'';
                                                        $rating13 = isset($ratingLevelText[$js_id][5]['translation_text'])?$ratingLevelText[$js_id][5]['translation_text']:'';
                                                       
                                                    }
                                                    
                                                    ?>
                                                    <li data-id="<?php echo $js_id; ?>" id="js-id-<?php echo $js_id; ?>" class="judgementS">
                                                        <h4><?php echo $cq_no_inKpa . $numToAlph[$js_no] . ". " . $js['judgement_statement_text']; ?></h4>
                                                        <hr>
                                                        <div class="clearfix">

                                                            <div class="questCont">
                                                                
                                                                
                                                                <?php if( $diagnostic_type == 1){
                                                                    ?>
                                                                        <strong class="inputTitle">Learning Walk:</strong><textarea autocomplete="off" class="form-control evidence-text" <?php echo $readOnlyText; ?> name="<?php echo $name; ?>[text][lw]" rows="1" placeholder="<?php echo $diagnosticLabels['Evidence_Txt']; ?>" cols="20"><?php echo isset($js['evidence_text_lw'])?$js['evidence_text_lw']:''; ?></textarea> 
                                                                        <strong class="inputTitle">Class Observations:</strong><textarea autocomplete="off" class="form-control evidence-text" <?php echo $readOnlyText; ?> name="<?php echo $name; ?>[text][co]" rows="1" placeholder="<?php echo $diagnosticLabels['Evidence_Txt']; ?>" cols="20"><?php echo isset($js['evidence_text_co'])?$js['evidence_text_co']:''; ?></textarea> 
                                                                        <strong class="inputTitle">Interactions:</strong><textarea autocomplete="off" class="form-control evidence-text" <?php echo $readOnlyText; ?> name="<?php echo $name; ?>[text][in]" rows="1" placeholder="<?php echo $diagnosticLabels['Evidence_Txt']; ?>" cols="20"><?php echo isset($js['evidence_text_in'])?$js['evidence_text_in']:''; ?></textarea> 
                                                                        <strong class="inputTitle">Book Look:</strong><textarea autocomplete="off" class="form-control evidence-text" <?php echo $readOnlyText; ?> name="<?php echo $name; ?>[text][bl]" rows="1" placeholder="<?php echo $diagnosticLabels['Evidence_Txt']; ?>" cols="20"><?php echo isset($js['evidence_text_bl'])?$js['evidence_text_bl']:''; ?></textarea> 
                                                                    <?php
                                                                }else{ ?>
                                                                     <textarea autocomplete="off" class="form-control evidence-text" <?php echo $readOnlyText; ?> name="<?php echo $name; ?>[text]" rows="1" placeholder="<?php echo $diagnosticLabels['Evidence_Txt']; ?>" cols="20"><?php echo $js['evidence_text']; ?></textarea> 
                                                                <?php } ?>
                                                            </div>
                                                            <div class="rightBoxpart">
                                                                <div class="padTopBox">
                                                                    <div class="row">
                                                                        <?php if($kpaBaseId == 1 && $diagnostic_type == 1){ ?>
                                                                                
                                                                                <span class="sptr"></span>
                                                                                 <div class="col-sm-6">
                                                                             <?php } else { ?>
                                                                                      <div class="col-sm-12">
                                                                             <?php } ?>
                                                                             <?php if($kpaBaseId == 1 && $diagnostic_type == 1){ ?>
                                                                                
                                                                                <h3>Availability and<br>Adequacy</h3>
                                                                             <?php } ?>
                                                                            
                                                                            <div class="ratingBox radioWrapper text-center">
                                                                                <strong class="mr2"><?php echo $diagnosticLabels['Your_Rating']; ?>:</strong>
                                                                                <div class="rate level1 vtip" title="<?php echo $rating1; ?>"><input type="radio" <?php echo $disabledText; ?> class="radio_js key-65" value="3" <?php echo ($js['numericRating'] == 3 )? 'checked="checked"' : ''; ?> name="<?php echo $name; ?>[value]" autocomplete="off"><i class="fa fa-circle-o"></i></div>
                                                                                <div class="rate sometimes vtip" title="<?php echo $rating2; ?>"><input type="radio" <?php echo $disabledText; ?> class="radio_js key-77" value="2" <?php echo ($js['numericRating'] == 2 )? 'checked="checked"' : ''; ?> name="<?php echo $name; ?>[value]" autocomplete="off"><i class="fa fa-circle-o"></i></div>
                                                                                <div class="rate mostly vtip" title="<?php echo $rating3; ?>"><input type="radio" <?php echo $disabledText; ?> class="radio_js key-83" value="1" <?php echo ($js['numericRating'] == 1 )? 'checked="checked"' : ''; ?> name="<?php echo $name; ?>[value]" autocomplete="off"><i class="fa fa-circle-o"></i></div>
                                                                                <?php if (!empty($cq['core_question_id'])) { ?>
                                                                                    <div class="rate rarely vtip" title="<?php echo $diagnosticLabels['Rarely']; ?>"><input type="radio" <?php echo $disabledText; ?> class="radio_js key-82" value="1" <?php echo ($js['numericRating'] == 1 || ((isset($assessment['assessment_type_id']) && $assessment['assessment_type_id'] == 4) && empty($js['numericRating']))) ? 'checked="checked"' : ''; ?> name="<?php echo $name; ?>[value]" autocomplete="off"><i class="fa fa-circle-o"></i></div>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                        <?php if($kpaBaseId == 1 && $diagnostic_type == 1){ ?>
                                                                        <div class="col-sm-6">
                                                                            <h3>Quality and <br>Usability</h3>
                                                                            <div class="ratingBox radioWrapper text-center">
                                                                                <strong class="mr2"><?php echo $diagnosticLabels['Your_Rating']; ?>:</strong>
                                                                                <div class="rate level1 vtip" title="<?php echo $rating11; ?>"><input type="radio" <?php echo $disabledText; ?> class="radio_js key-65" value="3" <?php echo ( isset($js['level2rating']) && $js['level2rating'] == 3) ? 'checked="checked"' : ''; ?> name="<?php echo $name; ?>[value1]" autocomplete="off"><i class="fa fa-circle-o"></i></div>
                                                                                <div class="rate sometimes vtip" title="<?php echo $rating12; ?>"><input type="radio" <?php echo $disabledText; ?> class="radio_js key-77" value="2" <?php echo ( isset($js['level2rating']) && $js['level2rating'] == 2) ? 'checked="checked"' : ''; ?> name="<?php echo $name; ?>[value1]" autocomplete="off"><i class="fa fa-circle-o"></i></div>
                                                                                <div class="rate mostly vtip" title="<?php echo $rating13; ?>"><input type="radio" <?php echo $disabledText; ?> class="radio_js key-83" value="1" <?php echo (isset($js['level2rating']) && $js['level2rating'] == 1) ? 'checked="checked"' : ''; ?> name="<?php echo $name; ?>[value1]" autocomplete="off"><i class="fa fa-circle-o"></i></div>
                                                                                <?php if (!empty($cq['core_question_id'])) { ?>
                                                                                    <div class="rate rarely vtip" title="<?php echo $diagnosticLabels['Rarely']; ?>"><input type="radio" <?php echo $disabledText; ?> class="radio_js key-82" value="1" <?php echo ($js['numericRating'] == 1 || ((isset($assessment['assessment_type_id']) && $assessment['assessment_type_id'] == 4) && empty($js['numericRating']))) ? 'checked="checked"' : ''; ?> name="<?php echo $name; ?>[value]" autocomplete="off"><i class="fa fa-circle-o"></i></div>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>

                                                                <div class="clr"></div>
                                                                <div class="upldHldr">                                                                                               
                                                                    <div class="inlineBtns">
                                                                        <?php if (!$isReadOnly) { ?> 
                                                                            <div class="fileUpload btn btn-primary mr0 vtip" title="Only jpeg, png, gif, jpg, avi, mp4, mov, doc, docx, txt, xls, xlsx, pdf, cvs, xml, pptx, ppt, cdr, mp3, wav type of files are allowed">
                                                                                <i class="fa fa-arrow-up"></i> <span><?php echo $diagnosticLabels['Evidence']; ?></span>
                                                                                <input type="file" autocomplete="off" <?php echo $readOnlyText; ?> multiple="multiple" title=" " class="upload uploadBtn">
                                                                            </div> 
                                                                        <?php } ?>

                                                                        <?php
                                                                        $isJsKNComplete = true;
                                                                        if (!empty($ajsns) && $ajsns != 0 && $assessment['role'] == 4) {
                                                                            if ($assessment['role'] == 4) {
                                                                                if (isset($ajsns[$js_id]) && count($ajsns[$js_id])) {
                                                                                    foreach ($ajsns[$js_id] as $akn_id => $akn) {
                                                                                        if (empty($akn['text_data'])) {
                                                                                            $isJsKNComplete = false;
                                                                                            $assessmentFilled = 0;
                                                                                            break;
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    $isJsKNComplete = false;
                                                                                    $assessmentFilled = 0;
                                                                                }
                                                                            }
                                                                            ?><?php
                                                                        } else
                                                                            $isJsKNComplete = false;
                                                                        ?>
                                                                        <?php if (isset($diagData) && $diagData['js_recommendations'] && $assessment['role'] == 4) { ?> <a class='keyRecLink btn btn-primary execUrl kR judgementS' id="kr_judgementS_<?php echo $js_id ?>"  href="?controller=diagnostic&action=keyrecommendations&type=judgement_statement&instance_id=<?php echo $js_id; ?>&assessment_id=<?php echo $assessment_id; ?>&assessor_id=<?php echo $assessor_id; ?>&lang_id=<?php echo $prefferedLanguage; ?>&external=<?php echo $external ?>&is_collaborative=<?php echo $is_collaborative ?>" ><i class="fa "></i><?php echo $diagnosticLabels['Key_Recommendations']; ?></a>
                                                                            <input name="js_aknotes[]" type="hidden" class='judgementS key-notes-val' id='judgementS_<?php echo $js_id; ?>' value="<?php $isJsKNComplete ? print 1 : print 0; ?>"><?php } ?> </div>
                                                                    <div class="filesWrapper">
                                                                        <?php
                                                                        $files = diagnosticModel::decodeFileArray($js['files']);
                                                                        foreach ($files as $file_id => $file_name) {
                                                              $presignedURL=getURL(UPLOAD_PATH.''.$file_name);
                                                                            echo '<div ' . $readOnlyText . ' class="filePrev uploaded vtip ext-' . diagnosticModel::getFileExt($file_name) . '" id="file-' . $file_id . '" title="' . $file_name . '">' . ($isReadOnly ? '' : '<span class="delete fa"></span>') . '<div class="inner"><a href="' . $presignedURL . '" target="_blank"> </a></div><input type="hidden" name="' . $name . '[files][]" value="' . $file_id . '"></div>';
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                                                                
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="clearfix">
                <?php if (!empty($assessment_id)) { ?><a class="fr nuibtn viewBtn vtip execUrl" title="Last saved data will be previewed" data-modalclass="modal-lg aPreview" href="?controller=diagnostic&action=assessmentPreview&assessment_id=<?php echo $assessment_id; ?>&assessor_id=<?php echo $assessor_id; ?>&kpa_id=<?php echo $kpa_id; ?>&lang_id=<?php echo $prefferedLanguage; ?>&external=<?php echo $external; ?>" ><?php echo $diagnosticLabels['View']; ?></a><?php } ?>
            </div>
        </div>
    </div>
<?php } ?>