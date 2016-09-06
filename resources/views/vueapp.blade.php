@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body">
                    <h1>Vue Test App</h1>


                    <div id="app">

                    	<pre>@{{ $data | json }}</pre>

                    	<div v-for="plan in plans">

                    		<plan :plan="plan" :active.sync="active" ></plan>

                    	</div>
                    </div>

                    <template id="plan-template">
                    	<div>
	                    	<span>@{{ plan.name }}</span>
	                    	<span>@{{ plan.price }}/month</span>
	                    	<button @click="setActivePlan" v-show="plan.price !== active.price">@{{ isUpgrade ? 'Upgrade' : 'Downgrade' }}</button>
	                    	<span v-else>
	                    		Selected
	                    		</span>
                    	</div>
                    </template>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="http://cdnjs.cloudflare.com/ajax/libs/vue/1.0.4/vue.js"></script>
<script>
new Vue({
	el: '#app',
	data: {
		plans: [
			{name: 'Enterprise', price: 100 },
			{name: 'Pro', price: 50 },
			{name: 'Personal', price: 10 },
			{name: 'Free', price: 0 },
		],
		active: {}
	},

	components: {
		plan: {
			template: '#plan-template',

			props: ['plan', 'active'],

			computed: {

				isUpgrade: function()
				{
					return this.plan.price > this.active.price;
				}
			},


			methods: {
				setActivePlan: function () {
					this.active = this.plan;
				}


			}
		}


	}
});
</script>

@endsection
