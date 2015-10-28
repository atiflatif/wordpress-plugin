<?php
/**
 * Plugin Name: EEXCESS_withform
 * Plugin URI: https://github.com/EEXCESS/eexcess
 * Description: Gives you the ability to enrich your blog with well-selected and high quality content
 * Version: 0.3
 * Author: Andreas Eisenkolb and Nils Witt
 * Author URI: https://github.com/AEisenkolb
 * Author URI: https://github.com/n-witt
 * License: Apache 2.0
 */
/*  Copyright 2014 University of Passau

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
?>
<?php
   add_action( 'admin_init', 'init_eexcess_plugin' );

   // Prepare stuff
   function init_eexcess_plugin() {
      global $pagenow;

      // Register the script first.
      wp_register_script( 'some_handle', plugins_url( '/js/eexcess-jquery-eventhandlers.js', __FILE__ ) );
      // Now we can localize the script with our data.
      $translation_array = array( 'pluginsPath' => plugin_dir_url( __FILE__ ) );
      wp_localize_script( 'some_handle', 'pluginURL', $translation_array );
      // The script can be enqueued now or later.
      wp_enqueue_script( 'some_handle' );

      // Load the scripts for the post creation / editing page
      if($pagenow == 'post-new.php' || $pagenow == 'post.php') {
         // init jQuery
         wp_enqueue_script('jquery');
         // init JavaScript
         wp_enqueue_script( 'eexcess-settings', plugins_url( '/js/eexcess-settings.js', __FILE__ ), array('jquery') );
         wp_enqueue_script( 'eexcess-pagination-script', plugins_url( '/js/lib/jquery.paginate.js', __FILE__), array('jquery') );
         wp_enqueue_script( 'eexcess-templating-script', plugins_url( '/js/lib/handlebars-v1.3.0.js', __FILE__), array('jquery') );
         wp_enqueue_script( 'eexcess-script', plugins_url( '/js/eexcess-script.js', __FILE__ ), array('jquery') );
         wp_enqueue_script( 'eexcess-jquery-plugins', plugins_url( '/js/eexcess-jquery-plugins.js', __FILE__ ), array('jquery') );
         //wp_enqueue_script( 'eexcess-jquery-eventhandlers', plugins_url( '/js/eexcess-jquery-eventhandlers.js', __FILE__ ), array('jquery') );
         //for citeproc
         wp_enqueue_script( 'eexcess-citeproc', plugins_url( '/js/lib/citationBuilder.js', __FILE__ ));
         // init styles
         
		 //Atif: Various style sheets included here for profile form option
		 wp_enqueue_style( 'eexcess-styles', plugins_url( '/styles/eexcess-styles.css', __FILE__ ) );
         wp_enqueue_style( 'onOffSwitch', plugins_url( '/styles/toggle-switch.css', __FILE__ ) );
      
	  wp_enqueue_style( 'bootstrap', plugins_url( '/styles/bootstrap.css', __FILE__ ) );
      wp_enqueue_style( 'bootstrap-theme', plugins_url( '/styles/bootstrap-theme.css', __FILE__ ) );
      wp_enqueue_style( 'datepicker', plugins_url( '/styles/datepicker.css', __FILE__ ) );
      wp_enqueue_style( 'jquery-ui', plugins_url( '/styles/jquery-ui.css', __FILE__ ) );
      wp_enqueue_style( 'privacy', plugins_url( '/styles/privacy.css', __FILE__ ) );
		wp_enqueue_style( 'jquery.tagit', plugins_url( '/styles/jquery.tagit.css', __FILE__ ) );

	  
	  
	  
	  
	  
	  }
   }

   add_action( 'add_meta_boxes', 'myplugin_add_meta_box' );
   /**
    * Adds a box to the main column on the Post edit screen.
    */
   function myplugin_add_meta_box() {
      // @see http://codex.wordpress.org/Function_Reference/add_meta_box
      add_meta_box(
         'eexcess_container', // id
         'EEXCESS', // title
         'eexcess_meta_box_callback', // callback
         'post' // post_type
      );
    }

   /**
    * Prints the box content.
    *
    * @param WP_Post $post The object for the current post/page.
    */
   function eexcess_meta_box_callback( $post ) { ?>
      <?php // List template ?>
      <script id="list-template" type="text/x-handlebars-template">
         <div id="recommendationList">
            <ul id="eexcess-recommendationList">
               {{#each result}}
                  <li data-id="{{documentBadge.id}}">
                     <div>
                        {{#if previewImage}}
                           <div class="eexcess-previewPlaceholder">
                              <a href="{{previewImage}}" target="_blank">
                                 <img src="{{previewImage}}" alt="thumbnail"></img>
                              </a>
                           </div>
                        {{else}}
                           <div class="eexcess-previewPlaceholder"></div>
                        {{/if}}
                        <div class="recommendationTextArea">
                           <a target="_blank" href="{{documentBadge.uri}}">{{title}}</a>
                           {{#if creator}}
                              <div class="creator">Creator: {{creator}}</div>
                           {{else}}
                              <div class="provider">Provider: {{documentBadge.provider}}</div>
                           {{/if}}
                           {{#if date}}
                              <div class="published">Published: {{date}}</div>
                           {{else}}
                              <div class="language">Language: {{language}}</div>
                           {{/if}}
                           {{#if collectionName}}
                              <input type="hidden" name="collectionName" value="{{collectionName}}">
                           {{/if}}
                           {{#if creator}}
                              <input type="hidden" name="creator" value="{{creator}}">
                           {{/if}}
                           {{#if description}}
                              <input type="hidden" name="description" value="{{description}}">
                           {{/if}}
                           {{#if eexcessURI}}
                              <input type="hidden" name="eexcessURI" value="{{documentBadge.uri}}">
                           {{/if}}
                           {{#if facets.year}}
                              <input type="hidden" name="facets.year" value="{{date}}">
                           {{/if}}
                           {{#if facets.language}}
                              <input type="hidden" name="facets.language" value="{{language}}">
                           {{/if}}
                           {{#if id}}
                              <input type="hidden" name="id" value="{{id}}">
                           {{/if}}
                           <input name="addAsCitation" class="button button-small" value="Add as Citation" style="width: 95px" onfocus="this.blur();">
                           {{#if previewImage}}
                              <input name="addAsImage" class="button button-small" value="Add as Image" style="width: 88px" onfocus="this.blur();">
                           {{/if}}
                        </div>
                     </div>
                  </li>
               {{/each}}
            </ul>
            <div class='pagination-container'>
               <div id='recommandationList-pagination'></div>
            </div>
         </div>
      </script>

      <input name="getRecommendations" class="button button-primary" id="getRecommendations" value="Get Recommendations">
      <input name="abortRequest" class="button button-primary" id="abortRequest" value="Abort Request">
      <select name="citationStyleDropDown" id="citationStyleDropDown" style="float: right">
         <option value="default" selected="selected">Citation Style (default Hyperlink)</option>
         <?php
            // corresponds to EEXCESS.citeproc.stylesDir from eexcess-settings.js.
            // unfortunatley there is no way to share that variable. At least AFAIK.
            $citeprocStylesPath = plugin_dir_path(__FILE__) . 'js/lib/citationStyles';
            if ($handle = opendir($citeprocStylesPath)) {
               while (false !== ($entry = readdir($handle))) {
                  if ($entry != "." && $entry != "..") {
                     $entry = str_replace(".csl", "", $entry);
                     echo '<option value="' . $entry . '">' . $entry . '</option>';
                  }
               }
               closedir($handle);
            }
         ?>
      </select>
      <div id="searchQueryReflection" class="searchQueryReflection">
         <span id="numResults"></span> Results on:
         <span id="searchQuery" style="color: #000000"></span>
      </div>
      <div id="content">
         <p>
            Get recommendations for keywords by using "#eexcess:Keyword#" inside the textarea.
            Furthermore, you can select parts of the text and then either click the "Get Recommendations"
            button or you can use the keyboard shortcut ctrl + e.
         </p>
         <div class='eexcess-spinner'></div>
         <div id='list'></div>
      </div>
      <div id="privacySettings">
         <hr>
         <!-- privacy settings thickbox-->
         <?php add_thickbox(); ?>
         <div id="privacyThickbox" style="display:none;">
            <br>
            <!-- tooglebutton-->
            <table class="privacySettings">
               <tr>
                  <td>Enable extended logging</td>
                  <td>
                     <input id="extendedLogging" class="cmn-toggle cmn-toggle-round" type="checkbox" checked>
                     <label for="extendedLogging"></label>
                  </td>
               </tr>
            		
			</table>
            <!-- /tooglebutton-->
         <!-- Atif: User profile panel -->
		 <div class="panel panel-primary">
			<div class="panel-heading" style="background-color: white;">
				<h3 class="panel-title"> User Profile
					<!--<span class="glyphicon glyphicon-user"></span> -->
				</h3>
			</div>
			<div class="panel-body">
	                    <!-- SOURCE SELECTION -->
				<div class="row">
	                        <!--     <div class="col-lg-12">
	                                <div id="source_selection" class="panel panel-info">
	                                    <div class="panel-heading">
	                                        <h3 class="panel-title">Source selection</h3>
	                                    </div>
	                                 </div>
	                            </div> -->
					<div class="col-lg-8">
						<!-- IDENTITY -->
						<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">
									Identity<span class="badge pull-right setting"></span>
								</h3>
							</div>
							<div class="panel-body">
								<div class="content">
									<form class="form-income">
										<div class="form-group">
											<label for="" class="control-label">Name</label>
											<div class="row">
												<div class="col-lg-2">
													<select  data-eexcess-profile-field="title" class="form-control">
														<option value=""></option>
														<option value="mr">Mr</option>
														<option value="miss">Miss</option>
														<option value="mrs">Mrs</option>
														<option value="ms">Ms</option>
													</select>
												</div>
												<div class="col-lg-5">
													<input data-eexcess-profile-field="firstname" type="text" class="form-control"
														placeholder="First name">
												</div>
												<div class="col-lg-5">
													<input data-eexcess-profile-field="lastname" type="text" class="form-control"
														placeholder="Last name">
												</div>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="control-label">Address</label>
											<div class="row">
												<div class="col-lg-12">
													<input data-eexcess-profile-field="address.line1" type="text" class="form-control" placeholder="Line 1">
												</div>
												<div class="col-lg-12">
													<input data-eexcess-profile-field="address.line2" type="text" class="form-control" placeholder="Line 2">
												</div>
												<div class="col-lg-4">
													<input data-eexcess-profile-field="address.zipcode" type="text" class="form-control" placeholder="Zip code">
												</div>
												<div class="col-lg-8">
													<input data-eexcess-profile-field="address.city" type="text" class="form-control" placeholder="City">
												</div>
												<div class="col-lg-12">
													<input data-eexcess-profile-field="address.country" type="text" class="form-control" placeholder="Country">
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<!-- /IDENTITY-->
					</div>
					<div class="col-lg-4">
						<!-- DEMOGRAPHICS -->
						<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">
									Demographics <span class="badge pull-right setting"></span>
								</h3>
							</div>
							<div class="panel-body">
								<div class="form-group">
									<label for="" class="control-label">Gender</label>
									<select data-eexcess-profile-field="gender" class="form-control">
										<option value=""></option>
										<option value="male">Male</option>
										<option value="female">Female</option>
									</select>
								</div>
								<div class="form-group">
									<label for="" class="control-label">Birthdate</label>
									<div class="input-group">
										<!--<span class="input-group-addon"><span class="glyphicon glyphicon-calendar icon-calendar"></span></span>-->
										<input data-eexcess-profile-field="birthdate" class="form-control datepicker" type="text" value="" data-date-format="yyyy-mm-dd" placeholder="Birthdate"></input>
									</div>
								</div>
							</div>
						</div>
						<!-- /DEMOGRAPHICS-->
					</div>
					
				<!--	<div class="col-lg-12">
						<!-- TOPICS -->
				<!--		<div id="topics" class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">
									Topics of interest <span class="badge pull-right setting"></span>
								</h3>
							</div>
	<!--						<div class="panel-body">
								<div class="form-group">
									<input class="form-control" type="text" placeholder="New topic"></input>
								</div>
								<div class="label-container well well-lg">
								</div>
							</div>-->
					<!--	<ul id="topicInput"></ul>
						</div>
						<!-- /TOPICS-->
				<!--	</div> -->
				</div>
			</div>
		</div>
		 <!-- Atif: User profile panel ends here-->
		 </div>
         <a href="#TB_inline?width=600&height=550&inlineId=privacyThickbox" title="Privacy Settings" class="thickbox">
            <input id="privacySettings"  style="width: 100px;" name="privacySettings" class="button button-small" value="Privacy Settings">
         
		 </a>
      </div>
      <!-- /privacy settings thickbox-->
   <?php }

   // Ajax action handler
   add_action( 'wp_ajax_get_recommendations', 'get_recommendations' );

   // Callback function for the Ajax call
   function get_recommendations() {
      $payload = $_POST['payload'];
      /**
       * URL: http://eexcess-dev.joanneum.at/eexcess-privacy-proxy/api/v1/recommend
       * Alternative URL: http://132.231.111.197:8080/eexcess-privacy-proxy/api/v1/recommend
       * METHOD: POST
       * DEV: http://eexcess-dev.joanneum.at/eexcess-federated-recommender-web-service-1.0-SNAPSHOT/recommender/recommend
       * Privacy Proxy
       */
      // new Format
      $proxyURL = "http://eexcess-dev.joanneum.at/eexcess-privacy-proxy-1.0-SNAPSHOT/api/v1/recommend";

      //dev
      //$proxyURL = "http://eexcess-dev.joanneum.at/eexcess-privacy-proxy/api/v1/recommend";

      //stable
      // $proxyURL = "http://eexcess.joanneum.at/eexcess-privacy-proxy/api/v1/recommend";

      // Create context for the API call
      $context = stream_context_create(array(
         'http' => array(
            'method' => 'POST',
            'header' => array("Content-Type: application/json", "Accept: application/json", "Origin: WP"),
            'content' => json_encode($payload)
         )
      ));
      // Send the request and return the result
      echo @file_get_contents($proxyURL, FALSE, $context);

	   die(); // this is required to return a proper result
   }


   // Ajax action handler
   add_action( 'wp_ajax_get_details', 'get_details' );

   // Callback function for the Ajax call
   function get_details() {
      $payload = $_POST['payload'];

      $proxyURL = "http://eexcess-dev.joanneum.at/eexcess-privacy-proxy-1.0-SNAPSHOT/api/v1/getDetails";
      
      // Create context for the API call
      $context = stream_context_create(array(
         'http' => array(
            'method' => 'POST',
            'header' => array("Content-Type: application/json", "Accept: application/json", "Origin: WP"),
            'content' => json_encode($payload)
         )
      ));
      // Send the request and return the result
      echo @file_get_contents($proxyURL, FALSE, $context);

	   die(); // this is required to return a proper result
   }


///////////advanced logging///////////////

add_action( 'wp_ajax_advanced_logging', 'advanced_logging' );

// Callback function for the Ajax call
function advanced_logging() {
   // Read the term form the POST variable
   $resource = $_POST['resource'];
   $query = $_POST['query'];
   $type = $_POST['type'];
   $timestamp = $_POST['timestamp'];
   $beenRecommended = $_POST['beenRecommended'];
   $action_taken = $_POST['action-taken'];
   $uuid = $_POST['uuid'];

   /**
    * URL: http://eexcess-dev.joanneum.at/eexcess-privacy-proxy/api/v1/recommend
    * Alternative URL: http://132.231.111.197:8080/eexcess-privacy-proxy/api/v1/recommend
    * METHOD: POST
    * DEV: http://eexcess-dev.joanneum.at/eexcess-federated-recommender-web-service-1.0-SNAPSHOT/recommender/recommend
    * Privacy Proxy
    */
   // new Format
   $proxyURL = "http://eexcess-dev.joanneum.at/eexcess-federated-recommender-web-service-1.0-SNAPSHOT/recommender/recommend";

   //dev
   //$proxyURL = "http://eexcess-dev.joanneum.at/eexcess-privacy-proxy/api/v1/log/rview";

   //stable
   //$proxyURL = "http://eexcess.joanneum.at/eexcess-privacy-proxy/api/v1/log/rview";

   // Data for the api call
   $postData = array(
      "resource" => $resource,
      "timestamp" => $timestamp,
      "type" => $type,
      "context" => array("query" => $query),
      "beenRecommended" => $beenRecommended,
      "action" => $action_taken,
      "uuid" => $uuid
   );


   // Create context for the API call
   $context = stream_context_create(array(
      'http' => array(
         'method' => 'POST',
         'header' => array("Content-Type: application/json", "Origin: WP"),
         'content' => json_encode($postData)
      )
   ));

   // Send the request and return the result
   echo @file_get_contents($proxyURL, FALSE, $context);
   //return HTTP-status to client
   echo($http_response_header[0]);

   die(); // this is required to return a proper result
}

///////////////////////


   // Hook for the WYSIWYG editor
   add_filter( 'tiny_mce_before_init', 'tiny_mce_before_init' );

   // Setting up the onKeyUp event for the WYSIWYG editor
   function tiny_mce_before_init( $initArray ) {
      $initArray['setup'] = "function(ed) {
         ed.onKeyUp.add(function(ed, e) {
            eexcessMethods.extractTerm(ed);
         });
         ed.onKeyDown.add(function(ed, e) {
            eexcessMethods.assessKeystroke(e);
         });
      }";
      return $initArray;
   }

   //adding a button to  tinyMCE
   add_action( 'admin_head', 'EEXCESS_add_tinymce' );
   function EEXCESS_add_tinymce() {
      global $typenow;

      // only on Post Type: post and page
      if( ! in_array( $typenow, array( 'post', 'page' ) ) )
           return ;

      // registers the method that registers our javascript file that implements our button
      add_filter( 'mce_external_plugins', 'EEXCESS_add_tinymce_plugin' );
      // registers the method that registers our button
      add_filter( 'mce_buttons', 'EEXCESS_add_tinymce_button' );
   }

   // inlcude the js for tinymce
   function EEXCESS_add_tinymce_plugin( $plugin_array ) {
      $plugin_array['EEXCESS_get_recommendations'] = plugins_url( 'js/tinyMCE_Get_Recommendations_Button.js', __FILE__ );
      $plugin_array['EEXCESS_alter_citations'] = plugins_url( 'js/tinyMCE_Alter_Citations_Button.js', __FILE__ );
      return $plugin_array;
   }

   // Add the button key for address via JS
   function EEXCESS_add_tinymce_button( $buttons ) {

      array_push( $buttons, 'Get_Recommendations_Button');
      array_push( $buttons, 'Alter_Citations_Button');
      return $buttons;
   }
?>
