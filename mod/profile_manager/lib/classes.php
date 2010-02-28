<?php 
	
	abstract class ProfileManagerCustomField extends ElggObject {
 		
		protected function initialise_attributes() {
			parent::initialise_attributes();
		}
	 
		public function __construct($guid = null) {
			parent::__construct($guid);
		}
		
		public function getOptions(){
			$options = "";
			
			// get options
			if(!empty($this->metadata_options))	{
				if($this->metadata_type != "multiselect"){
					$options = explode(",", "," . $this->metadata_options);
				} else {
					$options = explode(",", $this->metadata_options);
				}
			}
			
			return $options;
			
		}
	}
	
	class ProfileManagerCustomProfileField extends ProfileManagerCustomField {
 		
		const SUBTYPE = "custom_profile_field";
		
		protected function initialise_attributes() {
			parent::initialise_attributes();
			
			$this->attributes['subtype'] = self::SUBTYPE;
			$this->attributes['access_id'] = ACCESS_PUBLIC;
			$this->attributes['owner_guid'] = 0;
			$this->attributes['container_guid'] = 0;
		}
		
		public function getTitle(){
			// make title
			$title = $this->metadata_label;
			
			if(empty($title)){
				$trans_key = "profile:" . $this->metadata_name;
				if($trans_key != elgg_echo($trans_key)){
					$title = elgg_echo($trans_key);
				} else {
					$title = $this->metadata_name;
				}
			}
			
			return $title;
		}	
	}

	class ProfileManagerCustomGroupField extends ProfileManagerCustomField {
 		
		const SUBTYPE = "custom_group_field";
		
		protected function initialise_attributes() {
			parent::initialise_attributes();
		}
	 
		public function getTitle(){
			// make title
			$title = $this->metadata_label;
			
			if(empty($title)){
				$trans_key = "groups:" . $this->metadata_name;
				if($trans_key != elgg_echo($trans_key)){
					$title = elgg_echo($trans_key);
				} else {
					$title = $this->metadata_name;
				}
			}
			
			return $title;
		}		
	}
	
	class ProfileManagerCustomProfileType extends ElggObject {
 		
		const SUBTYPE = "custom_profile_type";
		
		protected function initialise_attributes() {
			parent::initialise_attributes();
			
			$this->attributes['subtype'] = self::SUBTYPE;
			$this->attributes['access_id'] = ACCESS_PUBLIC;
			$this->attributes['owner_guid'] = 0;
			$this->attributes['container_guid'] = 0;
		}
	 
		public function getTitle(){
			// make title
			$title = $this->metadata_label;
			
			if(empty($title)){
				$trans_key = "profile:profile_types:" . $this->metadata_name;
				if($trans_key != elgg_echo($trans_key)){
					$title = elgg_echo($trans_key);
				} else {
					$title = $this->metadata_name;
				}
			}
			
			return $title;
		}	

		public function getDescription(){
			$description = $this->metadata_description;
			if(empty($description)){
				$trans_key = "profile:profile_types:" . $this->metadata_name . ":description";
				if($trans_key != elgg_echo($trans_key)){
					$description = elgg_echo($trans_key);
				} 
			}
			
			return $description;
		}
	}
	
	class ProfileManagerCustomFieldCategory extends ElggObject {
 		
		const SUBTYPE = "custom_profile_field_category";
		
		protected function initialise_attributes() {
			parent::initialise_attributes();
			
			$this->attributes['subtype'] = self::SUBTYPE;
			$this->attributes['access_id'] = ACCESS_PUBLIC;
			$this->attributes['owner_guid'] = 0;
			$this->attributes['container_guid'] = 0;
		}
	 
		public function getTitle(){
			// make title
			$title = $this->metadata_label;
			
			if(empty($title)){
				$trans_key = "profile:categories:" . $this->metadata_name;
				if($trans_key != elgg_echo($trans_key)){
					$title = elgg_echo($trans_key);
				} else {
					$title = $this->metadata_name;
				}
			}
			
			return $title;
		}		
	}

?>