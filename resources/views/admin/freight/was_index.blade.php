@extends('layouts.app')

@section('content')
<style>
#package_input input { width: 6em; font-size:200%;}
</style>
<div class="container">
	<div class="row" id="app">
		<div class="col-md-10 col-md-offset-1">
	        <div class="panel panel-default">
	            <div class="panel-heading"><h3>Freight Calculator</h3></div>

	             <div class="panel-body">
	             	<!-- Postcode -->
	             	<div class="row">
	             		<div class="col-md-1">Location:</div>
	             	  	<div class="col-md-10 ">
	             	  	 <el-autocomplete style="width:400px"
						  v-model="location"
						  :fetch-suggestions="querySearchAsync"
						  placeholder="Postoce/suburb"
						  @select="handleLocationSelect"


						></el-autocomplete>
					  	</div>
					 </div>

					 <div class="row">
					  	<div class="col-md-1">Client:</div>
					  	<div class="col-md-10">
	             	  	<el-autocomplete  style="width:400px"
						  v-model="client"
						  :fetch-suggestions="lookupClientAsync"
						  placeholder="Client name"
						  @select="handleClientSelect"
						></el-autocomplete>
					  	</div>
	             	</div>

	             	<div class="row" style="padding:10px; background: #eee; margin: 10px 0">
	             		<div class="col-md-1"><strong>Suburb:</strong> </div><div class="col-md-5" v-text="suburb"></div>
	             		<div class="col-md-2"><strong>Postcode:</strong> </div><div class="col-md-1" v-text="postcode"></div>
	             		<div class="col-md-1"><strong>State:</strong> </div><div class="col-md-1" v-text="statecode"></div>

	             	</div>

	             	<!-- Packege Entry -->
	             	<div class="form-inline" v-on:keyup="formKeyup" id="package_input">
					  <div class="form-group" >

					    <input class="form-control" v-model.number="newPackage['length']"  placeholder="Length(cm)">
					  </div>
					   <div class="form-group">

					    <input class="form-control"  v-model.number="newPackage['width']"   placeholder="Width(cm)">
					  </div>
					  <div class="form-group">

					    <input class="form-control"  v-model.number="newPackage['height']"   placeholder="Height(cm)">
					  </div>
					  <div class="form-group">

					    <input class="form-control"  v-model.number="newPackage['weight']"  placeholder="Weight (kg)">
					  </div>
					  <div  v-show="inputMessage.length" v-text="inputMessage"></div>
					  <button type="submit" class="btn btn-default" @click="addPackage">Add</button>
					</div>

	             	<!-- Package List -->
	             	<template v-if="packages.length">
	             	<h3>Packages list</h3>
	             	<table class="table table-striped">
	             	<thead>
	             	<tr><th>#</th><th>L(cm)</th><th>W(cm)</th><th>H(cm)</th><th>Weight(Kg)</th></tr>
	             	</thead>
	             	<tbody>
		             	<template v-for="package in packages">
		             	<tr>
		             		<td v-text="package['id']"></td>
		             		<td v-text="package['width']"></td>
		             		<td v-text="package['length']"></td>
		             		<td v-text="package['height']"></td>
		             		<td v-text="package['weight']"></td>
		             	</tr>
		             	</template>
		            </tbody>
		            </table>
		            <p>Cubic weight (Kg): <span v-text="cubicWeight"></span><br />Actual weight (Kg):<span v-text="actualWeight"></span></p>
		            <button class="btn btn-primary" @click="getQuotes()">Get Quotes</button>
		            </template>


	             	<!-- Freight Summary -->




	             	<!-- Shipping  Options -->

	             	<h3>Quotations list</h3>
	             	<table class="table table-striped">
	             	<thead>
	             	<tr><th>Company</th><th>Method</th><th>Cost</th></tr>
	             	</thead>
	             	<tbody>
	             		<template v-for="(methods,companyKey) in quotes">
	             		<tr><td colspan=3 v-text="companyKey"></td></tr>

	             		<template v-for="(m_quotes,methodKey) in methods">
	             		<tr><td>&nbsp;</td><td colspan=2 v-text="methodKey"></td></tr>
	             			<template v-for="m_quote in m_quotes.quotes">
	             			<tr><td>&nbsp;</td><td>#@{{m_quote.package_id}}</td><td v-text="m_quote.cost.toFixed(2)"></td></tr>
	             			</template>
	             			<tr><td colspan="2" v-text="m_quotes.missed_packages.length ? 'missing packages:' + m_quotes.missed_packages.length  : ''"></td><td v-text="m_quotes.total.toFixed(2)"></td></tr>

	             		</template>

				        </template>



		            </tbody>
		            </table>

	             </div>
	        </div>
	    </div>
	</div>
</div>
@endsection
@section('script')

<script src="{{ elixir('js/freightcalc.js') }}"></script>


@endsection
