<?php

	class CBBS extends CBBS_CORE
	{
		var $m_SET;
		var $m_CONFIG;
		var $m_bState;

		// for ����
		var $DATA;

		function CBBS()
		{
			global $SCREEN;

			$this->m_CONFIG["BBS_LIST_DEC_BOTTOM"] = " ������������������������������������������������������������������������������&nbsp;".$SCREEN->m_strCRLF;
		}

		function PROPERTY( $PROPERTY, $VALUE )
		{
			$this->m_CONFIG[$PROPERTY] = $VALUE;
		}
		
		function ARGUMENT( $SET="" )
		{
			if( ! $SET )
				$SET = $this->m_SET;
			
			//$setstr  = "?&TABLE={$SET["TABLE"]}";
			$setstr = "?";
			
			if( $SET["PAGE"]   )		$setstr .= "&PAGE={$SET["PAGE"]}";
			if( $SET["SEARCH"]   )		$setstr .= "&SEARCH={$SET["SEARCH"]}";
			if( $SET["SEARCH_FIELD"] )	$setstr .= "&SEARCH_FIELD={$SET["SEARCH_FIELD"]}";
			if( $SET["ORDER_FIELD"] )	$setstr .= "&ORDER_FIELD={$SET["ORDER_FIELD"]}";
			if( $SET["ORDER"] )			$setstr .= "&ORDER={$SET["ORDER"]}";

			return $setstr;
		}

		function MAKEMENU( $SET="" )
		{
			global $DATA, $DATA_name, $DATA_size, $DATA_tmp_name, $DATA_type;
			global $MENU;
			
			if( $SET ) {
				$this->m_SET = $SET;
			}
			
			// P �޴� EDIT
			$PREV = $PHP_SELF.$this->ARGUMENT();
			$MENU->EDIT( "P", "URL", $PREV ); 
			$MENU->EDIT( "��", "URL", $PREV ); 
			
			// �޴�����
			$L  = Array ( "CODE" => "L",  "NAME" => "L",
						  "URL" => $PHP_SELF.$this->ARGUMENT()."&MODE=".BBS_MODE_LIST,
						  "BOTTOM" => true,
						  "TIP" => "�۸��" );

			$MENU->ITEMADD( $L );

			$L["CODE"] = "��";
			$L["BOTTOM"] = false;
			$MENU->ITEMADD( $L );
			

			// DATA�� �˻�
			$DATA[TABLE] = $SET[TABLE];

			if( trim($DATA[TABLE]) == "" ||
				trim($DATA[TITLE]) == "" ||
				trim($DATA[NAME]) == "" ||
				trim($DATA[PASSWORD]) == "" ) {

				$this->m_bState = false;
				return false;
			}
			
			$DATA["FILE_name"] = $DATA_name["FILE"];
			$DATA["FILE_size"] = $DATA_size["FILE"];
			$DATA["FILE_type"] = $DATA_type["FILE"];
			$DATA["FILE_tmp_name"] = $DATA_tmp_name["FILE"];
			
			// ��ûȭ�鿡 ���� �۾� ó��
			if( $DATA[MODE] == BBS_MODE_WRITE ) {

				if( $SET["PASSWORD"] != "" ) {
					if( $SET["PASSWORD"] != $DATA["PASSWORD"] ) {
						$this->m_bState = "�������";		
						return false;
					}
				}
				
				$this->m_bState = $this->WRITE( $DATA );

			} else if( $DATA[MODE] == BBS_MODE_REPLY ) {

				if( $SET["PASSWORD"] != "" ) {
					if( $SET["PASSWORD"] != $DATA["PASSWORD"] ) {
						$this->m_bState = "�������";		
						return false;
					}
				}

				$this->m_bState = $this->WRITE( $DATA, BBS_WRITE_REPLY );

			} else if( $DATA[MODE] == BBS_MODE_EDIT ) {
				
				$this->m_bState = $this->EDIT( $DATA );
				
			}
			
		}

		function SHOW()
		{
			global $DATA, $SCREEN;

			$m_bState = "{$this->m_bState}";
			
			if( ($m_bState == "PASSWORD") ) {
				echo " �� ���� ���� !!!";
				echo $SCREEN->m_strCRLF;
				echo $SCREEN->m_strCRLF;
				echo " ��ȣ�� Ʋ�Ƚ��ϴ�.";	
				echo $SCREEN->m_strCRLF;
				echo $this->m_CONFIG["BBS_LIST_DEC_BOTTOM"];
				return false;
			}
		
			if( $m_bState == "�������" ) {
				echo " �� ���� ���� !!!";
				echo $SCREEN->m_strCRLF;
				echo $SCREEN->m_strCRLF;
				echo " �۾��Ⱑ ���� �н����尡 �ƴմϴ�.";
				echo $SCREEN->m_strCRLF;
				echo $this->m_CONFIG["BBS_LIST_DEC_BOTTOM"];
				return false;
			}
			
			if( ! $this->m_bState ) {
				echo " �� ���� ���� !!!";
				echo $SCREEN->m_strCRLF;
				echo $SCREEN->m_strCRLF;
				echo " �ʼ� �Է� �׸��� ����� �Էµ��� �ʾҽ��ϴ�.";
				echo $SCREEN->m_strCRLF;
				echo $this->m_CONFIG["BBS_LIST_DEC_BOTTOM"];
				return false;
			}
			
			echo " ������ �Ϸ�.";
			echo $SCREEN->m_strCRLF;
			echo $SCREEN->m_strCRLF;
			echo " ������� ���ư����� P�� ��������.";
			echo $SCREEN->m_strCRLF;
			echo $this->m_CONFIG["BBS_LIST_DEC_BOTTOM"];
			return true;
		}
		
	}
	
?>