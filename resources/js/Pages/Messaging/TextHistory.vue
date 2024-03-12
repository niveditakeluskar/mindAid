<template>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-md-7" >
			<h4>Text History</h4>
			<ul class="timeline" id ="ajax-message-history" v-html="textHistory"></ul>
			<div class="d-flex justify-content-center"></div>   
		</div>
	</div>
</div>
</template>
<script>
import axios from 'axios';
export default {
	props: {
	  patientId: Number,
      moduleId: Number,
	},
	data() {
		return {
		textHistory:null,
		start_time:null,
		};
	},
	mounted() {
		this.start_time = document.getElementById('page_landing_times').value;
      this.fetchTextHistory();
	},
	methods: {
		async fetchTextHistory(){
			await axios.get(`/system/get-total-time/${this.patientId}/${this.moduleId}/${this.start_time}/total-time`)
				.then(response => {
					this.textHistory = response.data.history;
				})
				.catch(error => {
					console.error('Error fetching data:', error);
				});
		}
	}
};
</script>