@extends('layouts.app')

@section('content')
<style>
#package_input input { width: 6em; font-size:200%;}
</style>
<div class="container">
	<div class="row" id="app">
		<div class="col-md-10 col-md-offset-1">
	        <div class="panel panel-default">
	            <div class="panel-heading"><h3>Freight Calulator</h3></div>

	             <div class="panel-body">
	             	<!-- Postcode -->
	             	<div class="row">
	             	  <div class="col-md-10 col-md-offset-1">
	             	  	Destination: <el-autocomplete
						  v-model="location"
						  :fetch-suggestions="querySearchAsync"
						  placeholder="Postoce/suburb"
						  @select="handleLocationSelect"
						></el-autocomplete>

					  </div>

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
	             	<tr><th>L(cm)</th><th>W(cm)</th><th>H(cm)</th><th>Weight(Kg)</th></tr>
	             	</thead>
	             	<tbody>
		             	<template v-for="package in packages">
		             	<tr>
		             		<td v-text="package['width']"></td>
		             		<td v-text="package['length']"></td>
		             		<td v-text="package['height']"></td>
		             		<td v-text="package['weight']"></td>
		             	</tr>
		             	</template>
		            </tbody>
		            </table>
		            </template>


	             	<!-- Freight Summary -->
	             	<div v-text="cubicWeight"></div>
	             	<div v-text="actualWeight"></div>



	             	<!-- Shipping  Options -->

	             	<h3>Quotations list</h3>
	             	<table class="table table-striped">
	             	<thead>
	             	<tr><th>Company</th><th>Method</th><th>Cost</th></tr>
	             	</thead>
	             	<tbody>
	             		<template v-for="(methods,companyKey) in quotes">
	             		<tr><td colspan=3 v-text="companyKey"></td></tr>

	             		<template v-for="(quotes,methodKey) in methods">
	             		<tr><td>&nbsp;</td><td colspan=2 v-text="methodKey"></td></tr>
	             			<template v-for="quote in quotes">
	             			<tr><td>&nbsp;</td><td v-text="quote.package_id"></td><td v-text="quote.cost"></td></tr>
	             			</template>
	             			<tr><td colspan="2"></td><td v-text="quoteTotal(quotes)"></td></tr>

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
