<template>
	<div class="card">
		<div class="row" style="margin-bottom:5px;">
			<div class="col-lg-12 mb-3">
				<select name="top_stage_code_for_questionnaire" class="custom-select show-tick select2" v-model="selectedQuestionnaire" v-on:change="fetchMonthlyQuestion">
					<option value="">Select General Question</option>
					<option v-for="questionaire in questionnaire" :key="questionaire.id" :value="questionaire.id">
					{{ questionaire.description }}
					</option>
				</select>
			</div>
		</div>
		<div class="alert alert-success general_success" id="success-alert" style="display: none;">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong> General question data saved successfully! </strong><span id="text"></span>
		</div>
		<div v-html='decisionTree'></div>
		
		<div class="alert alert-success general_success" id="success-alert" style="display: none;">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong> General question data saved successfully! </strong><span id="text"></span>
		</div>
		<select name="bottom_stage_code_for_questionnaire" class="custom-select show-tick select2" v-model="selectedQuestionnaire">
			<option value="">Select General Question</option>
			<option v-for="questionaire in questionnaire" :key="questionaire.id" :value="questionaire.id" >
			{{ questionaire.description }}
			</option>
		</select>				
		<div style="padding-left: 20px; color:red; font-size:13px;"><b>Select additional applicable questions</b></div>
		<button type="button" class="btn  btn-primary m-1 nexttab" onclick="nexttab()" style='display:none;'>Next</button>
    </div>
</template>
<script>
import {ref} from 'vue';
import axios from 'axios';
const selectedQuestionnaire = ref(null); //"General Questions"; //ref(null);
export default {
	props: {
		patientId: Number,
		moduleId: Number,
		componentId: Number
	}, 
	data() {
		return {
			questionnaire: null,
			decisionTree: null,
			start_time: null,

		};
	},
	mounted() {
		this.fetchQuestionnaireData();
	},
	methods: {
		async fetchQuestionnaireData() {
			
			await axios.get(`/org/stage_code/${this.moduleId}/17/stage_code_list`)
				.then(response => {
					this.questionnaire = response.data;
					//console.log("questionnaire===>", this.questionnaire);
					this.selectedQuestionnaire = 38;
					this.fetchMonthlyQuestion();
					//console.log("gen"+selectedQuestionnaire.value);
				})
				.catch(error => {
					console.error('Error fetching data:', error);
				});
		},

		async fetchMonthlyQuestion(){
			$("#preloader").show();
			console.log('fetch questions'+this.selectedQuestionnaire);
			await axios.get(`/ccm/get-stepquestion/${this.moduleId}/${this.patientId}/${this.selectedQuestionnaire}/${this.componentId}/question_list`)
				.then(response => {
					this.decisionTree = response.data;
					console.log(document.getElementById('page_landing_times').value);
					//document.getElementsByClassName("timearr").value = document.getElementById('page_landing_times').value;
					this.start_time = document.getElementById('page_landing_times').value;
					console.log("timer="+this.start_time);
					this.savedGeneralQuestion();
				})
				.catch(error => {
					console.error('Error fetching data:', error);
				});
		},
		async savedGeneralQuestion(){
			await axios.get(`/ccm/get-savedQuestion/${this.moduleId}/${this.patientId}/${this.selectedQuestionnaire}/saved_question`)
				.then(response => {
					let genQuestion = response.data;
					var off = 1;
					for(var i = 0; i < genQuestion.length; i++){
						var editGq = JSON.parse(genQuestion[i].template);
						var javaObj = (genQuestion[i].template).replace("'", "&#39;");
						this.checkQuestion(editGq,off);
						off++;
					}
					$("#preloader").hide();
				})
				.catch(error => {
					console.error('Error fetching data:', error);
				});
		},
		checkQuestion(obj,i){
			var tree = JSON.stringify(obj);
            var treeobj = JSON.parse(tree);
			console.log(treeobj);
            for (var j = 1; j < 15; j++) {
				//Object.keys(treeobj.qs.opt).forEach(function(j) {
                if ((treeobj.qs.opt).hasOwnProperty(j)) {
                    var prnt = $('input[value="' + (treeobj.qs.q).replace( /[\r\n]+/gm, "" ) + '"]').parents('.mb-4').attr('id');
					console.log(prnt);
                    $('#' + prnt).find('input:radio[value="' + treeobj.qs.opt[j].val + '"], input:checkbox[value="' + treeobj.qs.opt[j].val + '"]').attr('checked', true).change();
                    if($('#' + prnt).find('input[type=text]').attr('id')){
                        var textid = $('#' + prnt).find('input[type=text]').attr('id');
                        $('#'+textid).val(treeobj.qs.opt[j].val);
                    }
                    var obj1 = treeobj.qs.opt;
                    this.renderEditquestion(obj1, j, i);
                }
            }
		},
		renderEditquestion(obj1, i, nct) {
			var tree = JSON.stringify(obj1);
            var treeobj = JSON.parse(tree);
            var l = 1;
			var objj = '';
			var z = 0;
            if (treeobj[i].hasOwnProperty('qs')) {
                Object.keys(treeobj[i].qs).forEach(function(key) {
                    if ((treeobj[i].qs[key]).hasOwnProperty('opt')) {
                        Object.keys(treeobj[i].qs[key].opt).forEach(function(j) {
                            if ((treeobj[i].qs[key].opt).hasOwnProperty(j)) {
                                var qval = (treeobj[i].qs[key].q).replace( /[\r\n]+/gm, "" );
                                var prnt = $('input[value="' + qval + '"]').parents('.mb-4').attr('id');
                                if(prnt != undefined){
                                    var string1= prnt; 
                                    var result = string1.substring(string1.indexOf('general_question') + 1);
                                    var strValue = result;
                                    strValue = strValue.replace('general_question','').replace('eneral_question','');
                                    var strValue1 = string1.replace('general_question'+strValue,'');
                                    if($('#options'+strValue+''+strValue1).find('input[type=text]').attr('id')){
                                        var inputbox = $('#options'+strValue+''+strValue1).find('input[type=text]').attr('id');
                                        $("#"+inputbox).val(treeobj[i].qs[key].opt[j].val);
                                    }
                                }
                            
                                $('#' + prnt).find('input:radio[value="' + treeobj[i].qs[key].opt[j].val + '"], input:checkbox[value="' + treeobj[i].qs[key].opt[j].val + '"]').attr('checked', true).change();
								objj = treeobj[i].qs[key].opt;
								z = j;
                               // this.renderEditquestion(objj, j, nct);
                            }
                        });
                    }
                });
            }
			if(z != 0){
				this.renderEditquestion(objj, z, nct);
			}
		},
		
	},
	
};


</script>