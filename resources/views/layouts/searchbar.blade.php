<form id="frmSearchbar" name="frmSearchbar" action="{{ url('experiences') }}" method="get">
    <div id="search_bar_container" style="background:#484848;">
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <input type="text" id="ssearch" name="search" class="form-control" />
                        </div>
                        <div class="col-md-4 form-group top_search">
                            <select class="form-control" id="sdestination" name="sdestination" style="border: 1px solid #fff;-webkit-appearance: none;-moz-appearance: none;text-indent: 0;">                        
                                <option value="">Destinations</option>
                                @foreach(\App\Http\Helpers\CommonHelper::get_site_destinations() as $destination)
                                <option value="{{ $destination->id }}" {{ (@$sdest == $destination->id) ? "selected":"" }}>{{ $destination->name." (".@$destination->total.")" }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group top_search">
                            <select class="form-control" id="scategory" name="scategory" style="border: 1px solid #fff;-webkit-appearance: none;-moz-appearance: none;text-indent: 0;">                        
                                <option value="">Category</option>
                                @foreach(\App\Http\Helpers\CommonHelper::get_site_categories() as $category)
                                <option value="{{ $category->id }}" {{ (@$scat == $category->id) ? "selected":"" }}>{{ $category->name." (".@$category->total.")" }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--div class="col-md-3 form-group">
                            <input type="text" class="date-pick form-control" id="sexp_date" name="sexp_date" placeholder="Experience Start Date" data-date-format="mm/dd/yyyy" value="{{ (@$sedate) ? \Carbon\Carbon::parse(@$sedate)->format("m/d/Y") : "" }}" style="border: 1px solid #fff;font-size: 16px;font-family: "Neue Frutiger W01";" />
                        </div-->
                        <div class="col-md-1 form-group text-left">
                            <button class="button_intro" style="background-color: #f36;color: #fff !important;">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- /search_bar-->