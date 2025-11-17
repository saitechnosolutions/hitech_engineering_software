@extends('layouts.app')
@section('main-content')

<style>
    .tree-container {
  width: 100%;
  height: 80vh;              /* adjustable */
  overflow: auto;            /* enables scroll */
  cursor: grab;
  border: 1px solid #ccc;
  background-color: #f9f9f9;
  position: relative;
}

.tree-container:active {
  cursor: grabbing;
}

.tree ul {
	padding-top: 20px; position: relative;

	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
}

.tree li {
	float: left; text-align: center;
	list-style-type: none;
	position: relative;
	padding: 20px 5px 0 5px;

	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
}

/*We will use ::before and ::after to draw the connectors*/

.tree li::before, .tree li::after{
	content: '';
	position: absolute; top: 0; right: 50%;
	border-top: 1px solid #ccc;
	width: 50%; height: 20px;
}
.tree li::after{
	right: auto; left: 50%;
	border-left: 1px solid #ccc;
}

/*We need to remove left-right connectors from elements without
any siblings*/
.tree li:only-child::after, .tree li:only-child::before {
	display: none;
}

/*Remove space from the top of single children*/
.tree li:only-child{ padding-top: 0;}

/*Remove left connector from first child and
right connector from last child*/
.tree li:first-child::before, .tree li:last-child::after{
	border: 0 none;
}
/*Adding back the vertical connector to the last nodes*/
.tree li:last-child::before{
	border-right: 1px solid #ccc;
	border-radius: 0 5px 0 0;
	-webkit-border-radius: 0 5px 0 0;
	-moz-border-radius: 0 5px 0 0;
}
.tree li:first-child::after{
	border-radius: 5px 0 0 0;
	-webkit-border-radius: 5px 0 0 0;
	-moz-border-radius: 5px 0 0 0;
}

/*Time to add downward connectors from parents*/
.tree ul ul::before{
	content: '';
	position: absolute; top: 0; left: 50%;
	border-left: 1px solid #ccc;
	width: 0; height: 20px;
}

.tree li a{
	border: 1px solid #ccc;
	padding: 5px 10px;
	text-decoration: none;
	color: #666;
	font-family: arial, verdana, tahoma;
	font-size: 15px;
	display: inline-block;

	border-radius: 5px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;

	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
}

/*Time for some hover effects*/
/*We will apply the hover effect the the lineage of the element also*/
.tree li a:hover, .tree li a:hover+ul li a {
	background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
}
/*Connector styles on hover*/
.tree li a:hover+ul li::after,
.tree li a:hover+ul li::before,
.tree li a:hover+ul::before,
.tree li a:hover+ul ul::before{
	border-color:  #94a0b4;
}

</style>

<x-breadcrumb
    :items="[
        ['label' => 'Hi-tech Engineering', 'url' => '#'],
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Production']
    ]"
    title="" />


                <div class="row mt-3">
                    <div class="col-md-12 col-lg-12 col-xl-12" >
                        <div class="card m-b-30 " >
                            <h6 class="card-header">{{ formatDate($batch->batch_date) }} - <span class="badge badge-success">{{ removeUnderscoreText($batch->priority) }}</span></h6>
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-12 align-self-center text-center">
                                        <div class="m-l-10" style="overflow-x:scroll">
                                            <div class="tree-container">
<div class="tree">
	<ul>
		<li>
			<a href="#">{{ formatDate($batch->batch_date) }} - <span class="badge badge-success">{{ removeUnderscoreText($batch->priority) }}</span></a>
			<ul>
                @if($quotations)
                    @foreach ($quotations as $quotation)
                    <li>
					<a href="#">{{ $quotation->quotation_no }}</a>
					<ul>
                        @if($quotation->quotationProducts)
                            @foreach ($quotation->quotationProducts as $products)
                                <li>
							    <a href="#" style="width:200px">{{ $products->product->product_name }} </a>
                            <ul>
                                @php
                                    $boms = App\Models\BomProcessTeams::with('team', 'bom')->where('product_id', $products->id)->get();
                                @endphp
                                @foreach ($boms as $bom)
                                    <li>
									    <a href="#">{{ $bom?->bom?->bom_name }}</a>
                                        @php
                                            $productTeams = App\Models\ProcessTeam::where('id', $bom->team_id)->get();
                                        @endphp
                                        <ul>
                                            @foreach($productTeams as $productTeam)
                                                <li><a>{{ $productTeam->team_name }}</a></li>
                                            @endforeach


                                        </ul>
								    </li>
                                @endforeach

							</ul>
						</li>
                            @endforeach
                        @endif

					</ul>
				</li>

                    @endforeach
                @endif

			</ul>
		</li>
	</ul>
</div>
</div>



                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>



@endsection



@push('scripts')
        <script src="/assets/js/quotation.js"></script>
        <script>
  const container = document.querySelector('.tree-container');
  let isDragging = false;
  let startX, startY, scrollLeft, scrollTop;

  container.addEventListener('mousedown', (e) => {
    isDragging = true;
    container.classList.add('active');
    startX = e.pageX - container.offsetLeft;
    startY = e.pageY - container.offsetTop;
    scrollLeft = container.scrollLeft;
    scrollTop = container.scrollTop;
  });

  container.addEventListener('mouseleave', () => {
    isDragging = false;
  });

  container.addEventListener('mouseup', () => {
    isDragging = false;
  });

  container.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    e.preventDefault();
    const x = e.pageX - container.offsetLeft;
    const y = e.pageY - container.offsetTop;
    const walkX = (x - startX);
    const walkY = (y - startY);
    container.scrollLeft = scrollLeft - walkX;
    container.scrollTop = scrollTop - walkY;
  });
</script>

@endpush
