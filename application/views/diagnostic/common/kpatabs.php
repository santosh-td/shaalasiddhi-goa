<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

						<?php 
						$activeKpa=isset($activeKpa)?$activeKpa:0;
						$kpa_no=0;
						foreach($kpas as $kpa_id=>$kpa){							
							$scheme_id = 'scheme-'.(isset($kpa['scheme_id'])?$kpa['scheme_id']:'');
							$kpa_no++;
							$isActive=$kpa_no==$activeKpa?true:false;
							$kpaFilled=1;
							include('kpatab.php');
							if(empty($kpaFilled))
								$assessmentFilled=0;
						}
						?>