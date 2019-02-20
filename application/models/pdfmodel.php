<?php
/**
 * Reasons: Manage data for pdf manage
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
class pdfModel extends Model{
	function getReportName($reportType)
	{
		$sql = "SELECT report_name FROM d_reports where report_id=?";
		return $this->db->get_row($sql,array($reportType));
	}
}