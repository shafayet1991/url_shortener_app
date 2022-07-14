<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Snipe\Safebrowsing\Facade\Safebrowsing;

class UrlShortenerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|Response
     */
    // all Url
    public function getUrlList(Request $request)
    {
        $urls = Url::all()->toArray();
        return response()->json(['status'=>200,'data'=>$urls], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function store(Request $request)
    {
        try
        {

            if(auth()->user()->id)
            {
                $longUrl = $request->get( 'url' );
                $newGeneratedUrl = $request->get( 'shortLink' );

                if ( $longUrl != '' || $newGeneratedUrl != '' )
                {
                    //GET array list of matched url
                    $urlFound = Url::where( 'old_url', $longUrl )->get( [ 'id', 'new_url', 'old_url' ] )->toArray();
                    if ( !empty( $urlFound ) )
                    {
                        $data = [
                            "status" => "Url already added, please try another",
                            "oldUrl" => $urlFound[ 0 ][ 'old_url' ],
                            "newUrl" => $urlFound[ 0 ][ 'new_url' ],
                        ];
                        return response()->json(['status'=>203,'data'=>$data], 203);
                    } else
                    {
                        Safebrowsing::addCheckUrls([$longUrl]);
                        //Safebrowsing::addCheckUrls(['http://malware.testing.google.test/testing/malware/']);
                        Safebrowsing::execute();
                        if(Safebrowsing::isFlagged($longUrl) == 1){
                            $data = [
                                "status" => "This Url seems not safe, Please try another!",
                                "oldUrl" => $longUrl,
                                "newUrl" => $newGeneratedUrl,
                            ];
                            return response()->json(['status'=>202,'data'=>$data], 202);
                        } else{
                            $urlTable          = new Url;
                            $urlTable->old_url = $longUrl;
                            $urlTable->new_url = $newGeneratedUrl;
                            $urlTable->user_ip = $_SERVER['REQUEST_URI'];
                            $urlTable->user_id = auth()->user()->id;
                            $urlTable->save();
                            if ( $urlTable->save() )
                            {
                                $data = [
                                    "status" => "New Url generated Successfully",
                                    "oldUrl" => $urlTable->old_url,
                                    "newUrl" => $urlTable->new_url,
                                ];
                                return response()->json(['status'=>200,'data'=>$data], 200);
                            }
                        }
                    }
                }
            }
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
        }
    }

    /**
     * handles the url
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function urlHandler(Request $request)
    {
        $uri = $request->get( 'url' );
        $url = Url::Where('new_url', 'like', '%' . $uri . '%')->get();
        if($uri =='' || $url =='' || count($url)==0){
            return response()->json(['status'=>202,'data'=>'No data found'], 202);
        }else{
            return response()->json(['status'=>200,'data'=> $url], 200);
        }

    }
}
