<template>
	<table class="table">
		<thead>
			<tr>
				<th scope="col">Time</th>
				<th scope="col" v-for="(day, index) in days" :key="index">{{ day }}</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="(time, i) in times" :key="i">
				<th>{{ time }}</th>
				<td v-for="(day, j) in days" :key="j">
					<input v-if="printing" type="checkbox" v-bind:id="id(day, i)">
					<div v-else class="custom-control custom-checkbox">
						<input type="checkbox" v-bind:name="name(day, i)" v-bind:id="id(day, i)" class="custom-control-input" value="1">
						<label v-bind:for="id(day, i)" class="custom-control-label"></label>
					</div>
				</td>
			</tr>
			<tr>
				<th>Any time</th>

				<td v-for="(day, j) in days" :key="j">
					<input v-if="printing" type="checkbox" v-bind:id="id(day, 'any')">
					<div v-else class="custom-control custom-checkbox">
						<input type="checkbox" v-bind:name="name(day, 'any')" v-bind:id="id(day, 'any')" @change="anyTime" class="custom-control-input" value="1" v-bind:data-day="day.toLowerCase()">
						<label v-bind:for="id(day, 'any')" class="custom-control-label"></label>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script>
export default {
	mounted () {
		if (this.printing != undefined && this.values) {
			var values = JSON.parse(this.values);
			for (var key in values) {
				$(`#${key.replace(/_/g, '-')}`).prop("checked", values[key]);
			}
		}
	},
	data () {
		return {
			days:  ["Mon", "Tue", "Wed", "Thu", "Fri"],
			times: ["8-10", "10-12", "12-2", "2-5"]
		}
	},
	methods: {
		anyTime (event) {
			var day = $(event.target).attr("data-day");
			for (var i in this.times) {
				let id = this.id(day, i);
				$(this.$el).find(`#${id}`).prop("disabled", $(event.target).is(":checked"));
			}
		},

		id (day, index) {
			return `contact-time-${day.toLowerCase()}-${index}`;
		},

		name (day, index) {
			return `${day.toLowerCase()}_${index}`;
		}
	},
	props: ["printing", "values"]
}
</script>
