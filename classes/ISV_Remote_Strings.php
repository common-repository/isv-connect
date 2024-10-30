<?php

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'ISV_Remote_Strings' ) ) {

	class ISV_Remote_Strings {

		/**
		 * Declare some strings used in the remote system, so we can translate them here via gettext
		 */
		public function init() {

			$intest = array(
				__( 'Key', 'isv-connect' ),
				__( 'PlanType', 'isv-connect' ),
				__( 'ID', 'isv-connect' ),
				__( 'Type', 'isv-connect' ),
				__( 'State', 'isv-connect' ),
				__( 'Status', 'isv-connect' ),
				__( 'StatusDescription', 'isv-connect' ),
				__( 'Start', 'isv-connect' ),
				__( 'Start_Date', 'isv-connect' ),
				__( 'Start_Time', 'isv-connect' ),
				__( 'Start_Unix', 'isv-connect' ),
				__( 'End', 'isv-connect' ),
				__( 'End_Date', 'isv-connect' ),
				__( 'End_Time', 'isv-connect' ),
				__( 'End_Unix', 'isv-connect' ),
				__( 'Duration', 'isv-connect' ),
				__( 'WeekOfYear', 'isv-connect' ),
				__( 'Category_ID', 'isv-connect' ),
				__( 'Category_Name', 'isv-connect' ),
				__( 'TrainingAreaCategory_ID', 'isv-connect' ),
				__( 'TrainingAreaCategory_Name', 'isv-connect' ),
				__( 'Trainer_ID', 'isv-connect' ),
				__( 'Trainer_Name', 'isv-connect' ),
				__( 'Vehicle_ID', 'isv-connect' ),
				__( 'Vehicle_Name', 'isv-connect' ),
				__( 'Description', 'isv-connect' ),
				__( 'PickupAddress', 'isv-connect' ),
				__( 'TotalCandidateCount', 'isv-connect' ),
				__( 'AvailableCandidateCount', 'isv-connect' ),
				__( 'Availability', 'isv-connect' ),
				__( 'OnlineAvailability', 'isv-connect' ),
			);

			$theory = array(
				__( 'Key', 'isv-connect' ),
				__( 'PlanType', 'isv-connect' ),
				__( 'ID', 'isv-connect' ),
				__( 'SetKey', 'isv-connect' ),
				__( 'Type', 'isv-connect' ),
				__( 'Index', 'isv-connect' ),
				__( 'BlockIndex', 'isv-connect' ),
				__( 'State', 'isv-connect' ),
				__( 'Status', 'isv-connect' ),
				__( 'StatusDescription', 'isv-connect' ),
				__( 'Start', 'isv-connect' ),
				__( 'Start_Date', 'isv-connect' ),
				__( 'Start_Time', 'isv-connect' ),
				__( 'Start_Unix', 'isv-connect' ),
				__( 'End', 'isv-connect' ),
				__( 'End_Date', 'isv-connect' ),
				__( 'End_Time', 'isv-connect' ),
				__( 'End_Unix', 'isv-connect' ),
				__( 'Duration', 'isv-connect' ),
				__( 'WeekOfYear', 'isv-connect' ),
				__( 'TheoryGroup_ID', 'isv-connect' ),
				__( 'TheoryGroup_Name', 'isv-connect' ),
				__( 'Trainer_ID', 'isv-connect' ),
				__( 'Trainer_Name', 'isv-connect' ),
				__( 'Product_ID', 'isv-connect' ),
				__( 'Product_Code', 'isv-connect' ),
				__( 'Product_Name', 'isv-connect' ),
				__( 'Description', 'isv-connect' ),
				__( 'PickupAddress', 'isv-connect' ),
				__( 'TotalCandidateCount', 'isv-connect' ),
				__( 'AvailableCandidateCount', 'isv-connect' ),
				__( 'Availability', 'isv-connect' ),
				__( 'OnlineAvailability', 'isv-connect' ),
			);

			$education = array(
				__( 'Key', 'isv-connect' ),
				__( 'Type', 'isv-connect' ),
				__( 'PackageID', 'isv-connect' ),
				__( 'ID', 'isv-connect' ),
				__( 'Start', 'isv-connect' ),
				__( 'Start_Date', 'isv-connect' ),
				__( 'Start_Time', 'isv-connect' ),
				__( 'Start_Unix', 'isv-connect' ),
				__( 'End', 'isv-connect' ),
				__( 'End_Date', 'isv-connect' ),
				__( 'End_Time', 'isv-connect' ),
				__( 'End_Unix', 'isv-connect' ),
				__( 'WeekOfYear', 'isv-connect' ),
				__( 'Name', 'isv-connect' ),
				__( 'Description', 'isv-connect' ),
				__( 'Article_ID', 'isv-connect' ),
				__( 'Price', 'isv-connect' ),
				__( 'Location', 'isv-connect' ),
				__( 'Availability', 'isv-connect' ),
				__( 'OnlineAvailability', 'isv-connect' ),
			);
		}
	}
}