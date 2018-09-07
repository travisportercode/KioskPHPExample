<?php

/*$xml=<<<XML
<note>
<to>Tove</to>
<from>Jani</from>
<heading>Reminder</heading>
<body>Don't forget me this weekend!</body>
</note>
XML;*/


$xml=<<<XML
<Performances>
<Performance>
        <pkg_no>0</pkg_no>
        <perf_no>2279</perf_no>
        <pkg_code xml:space="preserve">          </pkg_code>
        <perf_code>M151203P01</perf_code>
        <perf_date>2015-12-03T10:00:00-05:00</perf_date>
        <gross_availbility>0</gross_availbility>
        <availbility_by_customer>0</availbility_by_customer>
        <facility_no>20</facility_no>
        <met_criteria_in>Y    </met_criteria_in>
        <time_slot>3</time_slot>
        <description>Museum Admission</description>
        <on_sale_ind>Y</on_sale_ind>
        <bu>1</bu>
        <prod_season_no>922</prod_season_no>
        <no_name>N</no_name>
        <zmap_no>32</zmap_no>
        <start_dt>2015-06-01T00:00:00-04:00</start_dt>
        <end_dt>2015-12-14T10:00:00-05:00</end_dt>
        <first_dt>2015-07-17T10:00:00-04:00</first_dt>
        <last_dt>2016-06-30T10:00:00-04:00</last_dt>
        <facility_desc>Museum of Fine Arts, Boston</facility_desc>
        <weight>0</weight>
        <super_pkg_ind>N</super_pkg_ind>
        <fixed_seat_ind>N</fixed_seat_ind>
        <flex_ind>N</flex_ind>
        <prod_type>6</prod_type>
        <prod_type_desc>MFA Admission Paid</prod_type_desc>
        <season_no>18</season_no>
        <season_desc>FY16 Museum Admission</season_desc>
        <perf_status>1</perf_status>
        <perf_status_desc>On Sale</perf_status_desc>
        <relevance>0</relevance>
        <time_slot_desc>Morning</time_slot_desc>
      </Performance>
<Performance>
        <pkg_no>0</pkg_no>
        <perf_no>2926</perf_no>
        <pkg_code xml:space="preserve">          </pkg_code>
        <perf_code>M151203M01</perf_code>
        <perf_date>2015-12-03T10:00:00-05:00</perf_date>
        <gross_availbility>0</gross_availbility>
        <availbility_by_customer>0</availbility_by_customer>
        <facility_no>20</facility_no>
        <met_criteria_in>Y    </met_criteria_in>
        <time_slot>3</time_slot>
        <description>Member Admission</description>
        <on_sale_ind>Y</on_sale_ind>
        <bu>1</bu>
        <prod_season_no>928</prod_season_no>
        <no_name>N</no_name>
        <zmap_no>32</zmap_no>
        <start_dt>2015-06-18T00:00:00-04:00</start_dt>
        <end_dt>2015-12-03T21:45:00-05:00</end_dt>
        <first_dt>2015-07-01T10:00:00-04:00</first_dt>
        <last_dt>2016-06-30T10:00:00-04:00</last_dt>
        <facility_desc>Museum of Fine Arts, Boston</facility_desc>
        <weight>0</weight>
        <super_pkg_ind>N</super_pkg_ind>
        <fixed_seat_ind>N</fixed_seat_ind>
        <flex_ind>N</flex_ind>
        <prod_type>11</prod_type>
        <prod_type_desc>MFA Admission Member</prod_type_desc>
        <season_no>18</season_no>
        <season_desc>FY16 Museum Admission</season_desc>
        <perf_status>1</perf_status>
        <perf_status_desc>On Sale</perf_status_desc>
        <relevance>0</relevance>
        <time_slot_desc>Morning</time_slot_desc>
      </Performance>
<Performance>
        <pkg_no>0</pkg_no>
        <perf_no>10131</perf_no>
        <pkg_code xml:space="preserve">          </pkg_code>
        <perf_code>P151203B02</perf_code>
        <perf_date>2015-12-03T10:00:00-05:00</perf_date>
        <gross_availbility>0</gross_availbility>
        <availbility_by_customer>0</availbility_by_customer>
        <facility_no>19</facility_no>
        <met_criteria_in>Y    </met_criteria_in>
        <time_slot>3</time_slot>
        <description>Temporary Membership Card</description>
        <on_sale_ind>Y</on_sale_ind>
        <bu>1</bu>
        <prod_season_no>9975</prod_season_no>
        <no_name>N</no_name>
        <zmap_no>28</zmap_no>
        <start_dt>2015-12-03T00:00:00-05:00</start_dt>
        <end_dt>2015-12-04T00:00:00-05:00</end_dt>
        <first_dt>2015-07-01T10:00:00-04:00</first_dt>
        <last_dt>2016-06-30T10:00:00-04:00</last_dt>
        <facility_desc>Museum of Fine Arts, Boston</facility_desc>
        <weight>0</weight>
        <super_pkg_ind>N</super_pkg_ind>
        <fixed_seat_ind>N</fixed_seat_ind>
        <flex_ind>N</flex_ind>
        <prod_type>11</prod_type>
        <prod_type_desc>MFA Admission Member</prod_type_desc>
        <season_no>24</season_no>
        <season_desc>FY16 Passes</season_desc>
        <perf_status>1</perf_status>
        <perf_status_desc>On Sale</perf_status_desc>
        <relevance>0</relevance>
        <time_slot_desc>Morning</time_slot_desc>
      </Performances>
XML;


$object=simplexml_load_string($xml);
print_r($object);

?>