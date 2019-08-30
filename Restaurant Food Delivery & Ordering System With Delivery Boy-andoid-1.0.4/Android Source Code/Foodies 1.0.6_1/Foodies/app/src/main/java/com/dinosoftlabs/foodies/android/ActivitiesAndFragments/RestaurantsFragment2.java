package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.location.Address;
import android.location.Geocoder;
import android.os.Bundle;
import android.os.Handler;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.content.LocalBroadcastManager;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.SearchView;
import android.text.Html;
import android.util.Log;
import android.util.TypedValue;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.view.inputmethod.InputMethodManager;
import android.widget.Filter;
import android.widget.Filterable;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RatingBar;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.bartoszlipinski.recyclerviewheader2.RecyclerViewHeader;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.RestaurantMenuItems.RestaurantMenuItemsFragment;
import com.dinosoftlabs.foodies.android.Adapters.ServerImageParseAdapter;
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.GoogleMapWork.MapsActivity;
import com.dinosoftlabs.foodies.android.Models.RestaurantsModel;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;
import com.dinosoftlabs.foodies.android.Utils.TabLayoutUtils;
import com.gmail.samehadar.iosdialog.CamomileSpinner;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.text.DecimalFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.Timer;

import static android.content.Context.INPUT_METHOD_SERVICE;
import static com.facebook.FacebookSdk.getApplicationContext;


/**
 * Created by Nabeel on 12/12/2017.
 */

public class RestaurantsFragment2 extends Fragment implements GoogleApiClient.OnConnectionFailedListener,GoogleApiClient.ConnectionCallbacks{


    private ImageView res_filter;
    public static boolean FLAG_Restaurant_FRAGMENT;

    String latitude,longitude;

    private TextView title_city_tv;


    private int PLACE_PICKER_REQUEST = 1;

    private ArrayList<RestaurantsModel> GetDataAdapter1;
    private RecyclerView restaurant_recycler_view;
    private RecyclerViewHeader recyclerHeader;
    SwipeRefreshLayout refresh_layout;

    RecyclerView.LayoutManager recyclerViewlayoutManager;
    RestaurantsAdapter recyclerViewadapter;

    CamomileSpinner progressBar;
    String currentLoc;
    static SharedPreferences sharedPreferences;
    SearchView searchView;
    public static Timer swipeTimer;
    View layout;

    Handler handler = new Handler();
    Runnable timeCounter;
    String lat,lon,user_id;

    RelativeLayout transparent_layer,progressDialog;

     public static boolean isOpened;


    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

    }

    @Override
    public void setUserVisibleHint(boolean isVisibleToUser) {
        super.setUserVisibleHint(isVisibleToUser);
        if (isVisibleToUser) {
            if(ShowFavoriteRestFragment.FROM_FAVORITE) {
                getRestaurantList();
                ShowFavoriteRestFragment.FROM_FAVORITE = false;
            }
            //Write down your refresh code here, it will call every time user come to this fragment.
            //If you are using listview with custom adapter, just call notifyDataSetChanged().
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        getActivity().getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_HIDDEN);
        getActivity().getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_ADJUST_PAN);
        View view = inflater.inflate(R.layout.resturent_fragment2, container, false);
        FontHelper.applyFont(getContext(),getActivity().getWindow().getDecorView().getRootView(), AllConstants.verdana);

        FrameLayout frameLayout = view.findViewById(R.id.restaurent_main_layout);
        frameLayout.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {

                return true;
            }
        });

        sharedPreferences = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);
        currentLoc = sharedPreferences.getString(PreferenceClass.CURRENT_LOCATION_ADDRESS,"");
        lat = sharedPreferences.getString(PreferenceClass.LATITUDE,"");
        lon = sharedPreferences.getString(PreferenceClass.LONGITUDE,"");
         user_id = sharedPreferences.getString(PreferenceClass.pre_user_id,"");
        title_city_tv = view.findViewById(R.id.title_city_tv);
        if(currentLoc.isEmpty()){
            title_city_tv.setText("Kalma Chowk Lahore");
        }
        else {
            title_city_tv.setText(currentLoc);
        }

        if(lat.isEmpty()||lon.isEmpty()){
            lat = "31.4904023";
            lon = "74.2906989";
        }


        res_filter = view.findViewById(R.id.res_filter);
        res_filter.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

               Fragment restaurantMenuItemsFragment = new RestaurantSpecialityFrag();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.restaurent_main_layout, restaurantMenuItemsFragment,"ParentFragment").commit();
                FLAG_Restaurant_FRAGMENT = true;
            }
        });

        initUI(view);

        //   JSON_DATA_WEB_CALL();
        return view;
    }


    @Override
    public void onActivityCreated(Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        final InputMethodManager imm = (InputMethodManager) getActivity().getSystemService(INPUT_METHOD_SERVICE);
        imm.hideSoftInputFromWindow(getView().getWindowToken(), 0);
    }

    private void initUI(final View v){
        progressDialog = v.findViewById(R.id.progressDialog);
        transparent_layer = v.findViewById(R.id.transparent_layer);
        recyclerWithHeader(v);
        searchView = v.findViewById(R.id.floating_search_view);
        searchView.setQueryHint(Html.fromHtml("<font color = #dddddd>" + "Search Restaurant" + "</font>"));
        TextView searchText = (TextView)
                v.findViewById(android.support.v7.appcompat.R.id.search_src_text);
        searchText.setTextSize(TypedValue.COMPLEX_UNIT_SP,14);
        searchText.setPadding(0,0,0,0);
        LinearLayout searchEditFrame = (LinearLayout) searchView.findViewById(R.id.search_edit_frame); // Get the Linear Layout
// Get the associated LayoutParams and set leftMargin
        ((LinearLayout.LayoutParams) searchEditFrame.getLayoutParams()).leftMargin = 5;
        search(searchView,v);

        progressBar = v.findViewById(R.id.restaurantProgress);
        progressBar.start();

        if(RestaurantSpecialityFrag.RESTAURANT_SPECIALITY){
            getRestaurantListAgainstSpeciality();
            RestaurantSpecialityFrag.RESTAURANT_SPECIALITY = false;
        }
        else {
            getRestaurantList();
        }
        refresh_layout = v.findViewById(R.id.refresh_layout);
        refresh_layout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                if(handler!=null && timeCounter!=null){
                    handler.removeCallbacks(timeCounter);
                }
                getRestaurantList();
               // getRestList();

                refresh_layout.setRefreshing(false);

            }
        });

        title_city_tv.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

              startActivity(new Intent(getContext(),MapsActivity.class));
            }


        });

    }

    public void getRestaurantList(){
        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);
        GetDataAdapter1 = new ArrayList<>();
        RequestQueue queue = Volley.newRequestQueue(getApplicationContext());


        Calendar c = Calendar.getInstance();
        System.out.println("Current time => "+c.getTime());

        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        String formattedDate = df.format(c.getTime());

        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("lat", lat);
            jsonObject.put("long", lon);
            jsonObject.put("current_time",formattedDate);
            jsonObject.put("user_id",user_id);

            Log.e("Obj",jsonObject.toString());

        } catch (JSONException e) {
            e.printStackTrace();
        }

// Request a string response from the provided URL.
        JsonObjectRequest jsonObjReq = new JsonObjectRequest(Request.Method.POST,
                Config.SHOW_RESTAURANTS,jsonObject,
                new Response.Listener<JSONObject>() {

                    @Override
                    public void onResponse(JSONObject response) {

                        Log.d("JSONPost", response.toString());
                        String strJson =  response.toString();
                        JSONObject jsonResponse = null;
                        try {
                            jsonResponse = new JSONObject(strJson);

                            Log.d("JSONPost", jsonResponse.toString());

                            int code_id = Integer.parseInt(jsonResponse.optString("code"));

                            if (code_id == 200) {

                                JSONObject json = new JSONObject(jsonResponse.toString());
                                JSONArray jsonarray = json.getJSONArray("msg");

                                for (int i = 0; i < jsonarray.length(); i++) {

                                    JSONObject json1 = jsonarray.getJSONObject(i);

                                    JSONObject jsonObjRestaurant = json1.getJSONObject("Restaurant");
                                    JSONObject jsonObjCurrency = json1.getJSONObject("Currency");
                                    String symbol = jsonObjCurrency.optString("symbol");
                                    JSONObject jsonObjTax = json1.getJSONObject("Tax");
                                    JSONObject jsonObjRating = null;
                                    try {
                                        jsonObjRating = json1.getJSONObject("TotalRatings");
                                    } catch (JSONException ignored) {
                                        ignored.getCause();
                                    }

                                    JSONObject jsonObjDistance = json1.getJSONObject("0");
                                    // JSONObject ratingObj = jsonObjDistance.getJSONObject("TotalRatings");
                                    String distance = jsonObjDistance.optString("distance");
                                    RestaurantsModel RestaurantObj = new RestaurantsModel();
                                    RestaurantObj.setRestaurant_name(jsonObjRestaurant.optString("name"));
                                    RestaurantObj.setRestaurant_slogen(jsonObjRestaurant.optString("slogan"));
                                    RestaurantObj.setRestaurant_about(jsonObjRestaurant.optString("about"));
                                    RestaurantObj.setRestaurant_fee(jsonObjRestaurant.optString("delivery_fee"));
                                    RestaurantObj.setRestaurant_image(jsonObjRestaurant.optString("image"));
                                    RestaurantObj.setRestaurant_id(jsonObjRestaurant.optString("id"));
                                    RestaurantObj.setRestaurant_phone(jsonObjRestaurant.optString("phone"));
                                    RestaurantObj.setRestaurant_cover(jsonObjRestaurant.optString("cover_image"));
                                    RestaurantObj.setRestaurant_isFav(jsonObjRestaurant.optString("favourite"));
                                    RestaurantObj.setPromoted(jsonObjRestaurant.optString("promoted"));
                                    RestaurantObj.setPreparation_time(jsonObjRestaurant.optString("preparation_time"));
                                    String distanceKM = String.valueOf(new DecimalFormat("##.#").format(Double.parseDouble(distance) * 1.6)) + " KM";
                                    RestaurantObj.setRestaurant_distance(distanceKM);

                                    if (jsonObjRating == null) {

                                        RestaurantObj.setRestaurant_avgRating("0.00");
                                        RestaurantObj.setRestaurant_totalRating("0.00");
                                    } else {
                                        RestaurantObj.setRestaurant_avgRating(jsonObjRating.optString("avg"));
                                    }
                                    RestaurantObj.setRestaurant_currency(jsonObjCurrency.optString("symbol"));
                                    RestaurantObj.setRestaurant_tax(jsonObjTax.optString("tax"));
                                    RestaurantObj.setDelivery_fee_per_km(jsonObjTax.optString("delivery_fee_per_km"));
                                    RestaurantObj.setMin_order_price(jsonObjRestaurant.optString("min_order_price"));
                                    RestaurantObj.setRestaurant_restaurant_menu_style(jsonObjRestaurant.optString("menu_style"));
                                    RestaurantObj.setDeliveryFee_Range(jsonObjRestaurant.optString("delivery_free_range"));
                                    RestaurantObj.setDeliveryTime(jsonObjTax.getString("delivery_time"));
                                 /*  if (ratingObj!=null){
                                        RestaurantObj.setRestaurant_avgRating(ratingObj.getString("avg"));
                                    }
                                    else if(ratingObj==null){
                                        RestaurantObj.setRestaurant_avgRating("0.00");
                                    }*/

                                    GetDataAdapter1.add(RestaurantObj);
                                }

                                if (GetDataAdapter1 != null) {

                                    recyclerViewadapter = new RestaurantsAdapter(GetDataAdapter1, getContext());
                                    restaurant_recycler_view.setAdapter(recyclerViewadapter);
                                    recyclerViewadapter.notifyDataSetChanged();
                                    progressDialog.setVisibility(View.GONE);
                                    TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                                    transparent_layer.setVisibility(View.GONE);
                                    recyclerViewadapter.setOnItemClickListner(new OnItemClickListner() {
                                        @Override
                                        public void OnItemClicked(View view, final int position) {

                                         //   RestaurantsModel model = (RestaurantsModel) recyclerViewadapter.getItem(position);

                                           try {
                                               SharedPreferences.Editor editor = sharedPreferences.edit();
                                               editor.putString(PreferenceClass.RESTAURANT_NAME, GetDataAdapter1.get(position).getRestaurant_name());
                                               editor.putString(PreferenceClass.RESTAURANT_SALOGON, GetDataAdapter1.get(position).getRestaurant_salgon());
                                               editor.putString(PreferenceClass.RESTAURANT_IMAGE, GetDataAdapter1.get(position).getRestaurant_image());
                                               editor.putString(PreferenceClass.RESTAURANT_ID, GetDataAdapter1.get(position).getRestaurant_id());
                                               editor.putString(PreferenceClass.RESTAURANT_ABOUT, GetDataAdapter1.get(position).getRestaurant_about());
                                               editor.putString(PreferenceClass.RESTAURANT_RATING, GetDataAdapter1.get(position).getRestaurant_avgRating());
                                               editor.putString(PreferenceClass.RESTAURANT_ITEM_TAX,GetDataAdapter1.get(position).getRestaurant_tax());
                                               editor.putString(PreferenceClass.RESTAURANT_ITEM_FEE,GetDataAdapter1.get(position).getRestaurant_fee());
                                               editor.putString(PreferenceClass.DELIVERY_FEE_PER_KM,GetDataAdapter1.get(position).getDelivery_fee_per_km());
                                               editor.putString(PreferenceClass.MINIMUM_ORDER_PRICE,GetDataAdapter1.get(position).getMin_order_price());
                                               editor.putString(PreferenceClass.DELIVERY_FEE_RANGE,GetDataAdapter1.get(position).getDeliveryFee_Range());
                                               editor.putString(PreferenceClass.RESTAURANT_SYMBOL,GetDataAdapter1.get(position).getRestaurant_currency());
                                               editor.putString(PreferenceClass.DELIVERY_FEE_RANGE,GetDataAdapter1.get(position).getDeliveryFee_Range());
                                               editor.commit();

                                               Fragment restaurantMenuItemsFragment = new RestaurantMenuItemsFragment();
                                               FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                                               transaction.add(R.id.restaurent_main_layout, restaurantMenuItemsFragment, "parent").commit();
                                               if(swipeTimer!=null){
                                                   swipeTimer.cancel();
                                                   swipeTimer.purge();
                                               }
                                           }catch (IndexOutOfBoundsException e){
                                               e.getCause();
                                           }
                                        }
                                    });

                                }


                                } else {
                                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                                transparent_layer.setVisibility(View.GONE);
                                progressDialog.setVisibility(View.GONE);
                                    JSONObject json = new JSONObject(jsonResponse.toString());
                                  //  Toast.makeText(getApplicationContext(), json.optString("msg"), Toast.LENGTH_SHORT).show();
                                }

                                //JSONArray jsonMainNode = jsonResponse.optJSONArray("msg");                            }
                            } catch(JSONException e){
                                e.printStackTrace();
                            TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                            transparent_layer.setVisibility(View.GONE);
                            progressDialog.setVisibility(View.GONE);
                            }


                        //pDialog.hide();
                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                //  ed_progress.setVisibility(View.GONE);
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
               // Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
            }
        }){
            @Override
            public String getBodyContentType() {
                return "application/json; charset=utf-8";
            }

            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                HashMap<String, String> headers = new HashMap<String, String>();
                headers.put("api-key", "2a5588cf-4cf3-4f1c-9548-cc1db4b54ae3");
                return headers;
            }
        };

// Add the request to the RequestQueue.

        jsonObjReq.setRetryPolicy(new DefaultRetryPolicy(
                35000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));

        queue.add(jsonObjReq);

    }

    public void getRestaurantListAgainstSpeciality(){
        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        transparent_layer.setVisibility(View.VISIBLE);
        GetDataAdapter1 = new ArrayList<>();
        RequestQueue queue = Volley.newRequestQueue(getContext());
        String lat = sharedPreferences.getString(PreferenceClass.LATITUDE,"");
        String lon = sharedPreferences.getString(PreferenceClass.LONGITUDE,"");
        String user_id = sharedPreferences.getString(PreferenceClass.pre_user_id,"");
        String speciality = sharedPreferences.getString(PreferenceClass.RESTAURANT_SPECIALITY,"");

        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("lat",lat);
            jsonObject.put("long",lon);
            jsonObject.put("user_id","");
            jsonObject.put("speciality",speciality);

            Log.e("Obj",jsonObject.toString());

        } catch (JSONException e) {
            e.printStackTrace();
        }

// Request a string response from the provided URL.
        JsonObjectRequest jsonObjReq = new JsonObjectRequest(Request.Method.POST,
                Config.SHOW_REST_AGAINST_SPECIALITY,jsonObject,
                new Response.Listener<JSONObject>() {

                    @Override
                    public void onResponse(JSONObject response) {

                        Log.d("JSONPost", response.toString());
                        String strJson =  response.toString();
                        JSONObject jsonResponse = null;
                        try {
                            jsonResponse = new JSONObject(strJson);

                            Log.d("JSONPost", jsonResponse.toString());

                            int code_id = Integer.parseInt(jsonResponse.optString("code"));

                            if (code_id == 200) {

                                JSONObject json = new JSONObject(jsonResponse.toString());
                                JSONArray jsonarray = json.getJSONArray("msg");

                                for (int i = 0; i < jsonarray.length(); i++) {

                                    JSONObject json1 = jsonarray.getJSONObject(i);

                                    JSONObject jsonObjRestaurant = json1.getJSONObject("Restaurant");
                                    JSONObject jsonObjCurrency = json1.getJSONObject("Currency");
                                    String symbol = jsonObjCurrency.optString("symbol");
                                    JSONObject jsonObjTax = json1.getJSONObject("Tax");
                                    JSONObject jsonObjRating = null;
                                    try {
                                        jsonObjRating = json1.getJSONObject("TotalRatings");
                                    } catch (JSONException ignored) {
                                        ignored.getCause();
                                    }

                                    JSONObject jsonObjDistance = json1.getJSONObject("0");
                                    // JSONObject ratingObj = jsonObjDistance.getJSONObject("TotalRatings");
                                    String distance = jsonObjDistance.optString("distance");
                                    RestaurantsModel RestaurantObj = new RestaurantsModel();
                                    RestaurantObj.setRestaurant_name(jsonObjRestaurant.optString("name"));
                                    RestaurantObj.setRestaurant_slogen(jsonObjRestaurant.optString("slogan"));
                                    RestaurantObj.setRestaurant_about(jsonObjRestaurant.optString("about"));
                                    RestaurantObj.setRestaurant_fee(symbol + jsonObjRestaurant.optString("delivery_fee"));
                                    RestaurantObj.setRestaurant_image(jsonObjRestaurant.optString("image"));
                                    RestaurantObj.setRestaurant_id(jsonObjRestaurant.optString("id"));
                                    RestaurantObj.setRestaurant_phone(jsonObjRestaurant.optString("phone"));
                                    RestaurantObj.setRestaurant_cover(jsonObjRestaurant.optString("cover_image"));
                                    RestaurantObj.setRestaurant_isFav(jsonObjRestaurant.optString("favourite"));
                                    RestaurantObj.setPromoted(jsonObjRestaurant.optString("promoted"));
                                    RestaurantObj.setPreparation_time(jsonObjRestaurant.optString("preparation_time"));
                                    String distanceKM = String.valueOf(new DecimalFormat("##.#").format(Double.parseDouble(distance) * 1.6)) + " KM";
                                    RestaurantObj.setRestaurant_distance(distanceKM);

                                    if (jsonObjRating == null) {

                                        RestaurantObj.setRestaurant_avgRating("0.00");
                                        RestaurantObj.setRestaurant_totalRating("0.00");
                                    } else {
                                        RestaurantObj.setRestaurant_avgRating(jsonObjRating.optString("avg"));
                                    }
                                    RestaurantObj.setRestaurant_currency(jsonObjCurrency.optString("symbol"));
                                    RestaurantObj.setRestaurant_tax(jsonObjTax.optString("tax"));
                                    String tax = jsonObjTax.optString("tax");
                                    RestaurantObj.setDelivery_fee_per_km(jsonObjTax.optString("delivery_fee_per_km"));
                                    RestaurantObj.setDeliveryTime(jsonObjTax.getString("delivery_time"));
                                    RestaurantObj.setMin_order_price(jsonObjRestaurant.optString("min_order_price"));
                                    RestaurantObj.setRestaurant_restaurant_menu_style(jsonObjRestaurant.optString("menu_style"));
                                    RestaurantObj.setDeliveryFee_Range(jsonObjRestaurant.optString("delivery_free_range"));

                                 /*  if (ratingObj!=null){
                                        RestaurantObj.setRestaurant_avgRating(ratingObj.getString("avg"));
                                    }
                                    else if(ratingObj==null){
                                        RestaurantObj.setRestaurant_avgRating("0.00");
                                    }*/


                                    GetDataAdapter1.add(RestaurantObj);
                                }

                                if (GetDataAdapter1 != null) {
                                    recyclerViewadapter = new RestaurantsAdapter(GetDataAdapter1, getContext());
                                    restaurant_recycler_view.setAdapter(recyclerViewadapter);
                                    recyclerViewadapter.notifyDataSetChanged();
                                    progressDialog.setVisibility(View.GONE);
                                    TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                                    transparent_layer.setVisibility(View.GONE);
                                    recyclerViewadapter.setOnItemClickListner(new OnItemClickListner() {
                                        @Override
                                        public void OnItemClicked(View view, final int position) {

                                            SharedPreferences.Editor editor = sharedPreferences.edit();
                                            editor.putString(PreferenceClass.RESTAURANT_NAME, GetDataAdapter1.get(position).getRestaurant_name());
                                            editor.putString(PreferenceClass.RESTAURANT_SALOGON, GetDataAdapter1.get(position).getRestaurant_salgon());
                                            editor.putString(PreferenceClass.RESTAURANT_IMAGE, GetDataAdapter1.get(position).getRestaurant_image());
                                            editor.putString(PreferenceClass.RESTAURANT_ID, GetDataAdapter1.get(position).getRestaurant_id());
                                            editor.putString(PreferenceClass.RESTAURANT_ABOUT, GetDataAdapter1.get(position).getRestaurant_about());
                                            editor.putString(PreferenceClass.RESTAURANT_ITEM_TAX,GetDataAdapter1.get(position).getRestaurant_tax());
                                            editor.putString(PreferenceClass.RESTAURANT_ITEM_FEE,GetDataAdapter1.get(position).getRestaurant_fee());
                                            editor.putString(PreferenceClass.RESTAURANT_RATING, GetDataAdapter1.get(position).getRestaurant_avgRating());
                                            editor.putString(PreferenceClass.DELIVERY_FEE_PER_KM,GetDataAdapter1.get(position).getDelivery_fee_per_km());
                                            editor.putString(PreferenceClass.MINIMUM_ORDER_PRICE,GetDataAdapter1.get(position).getMin_order_price());
                                            editor.putString(PreferenceClass.DELIVERY_FEE_RANGE,GetDataAdapter1.get(position).getDeliveryFee_Range());
                                            editor.putString(PreferenceClass.RESTAURANT_SYMBOL,GetDataAdapter1.get(position).getRestaurant_currency());
                                            editor.commit();

                                            Fragment restaurantMenuItemsFragment = new RestaurantMenuItemsFragment();
                                            FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                                            transaction.add(R.id.restaurent_main_layout, restaurantMenuItemsFragment, "parent").commit();
                                        }
                                    });

                                }


                            } else {
                                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                                transparent_layer.setVisibility(View.GONE);
                                progressDialog.setVisibility(View.GONE);
                                JSONObject json = new JSONObject(jsonResponse.toString());
                             //   Toast.makeText(getContext(), json.optString("msg"), Toast.LENGTH_SHORT).show();
                            }

                            //JSONArray jsonMainNode = jsonResponse.optJSONArray("msg");                            }
                        } catch(JSONException e){
                            e.printStackTrace();
                        }


                        //pDialog.hide();
                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                //  ed_progress.setVisibility(View.GONE);
                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
              //  Toast.makeText(getContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
            }
        }){
            @Override
            public String getBodyContentType() {
                return "application/json; charset=utf-8";
            }

            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                HashMap<String, String> headers = new HashMap<String, String>();
                headers.put("api-key", "2a5588cf-4cf3-4f1c-9548-cc1db4b54ae3");
                return headers;
            }
        };

// Add the request to the RequestQueue.

        jsonObjReq.setRetryPolicy(new DefaultRetryPolicy(
                35000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));

        queue.add(jsonObjReq);

    }



    private void search(final SearchView searchView, final View v) {

        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {

                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {

                if (newText.length() > 0) {
                    // Search
                    if (recyclerViewadapter != null) recyclerViewadapter.getFilter().filter(newText);
                    recyclerHeader.setVisibility(View.GONE);
                     recyclerHeader.detach();
                } else {
                    // Do something when there's no input
                    recyclerHeader.setVisibility(View.VISIBLE);
//                    recyclerWithHeader(v);
                    recyclerHeader.attachTo(restaurant_recycler_view);
                    recyclerViewadapter.setmFilteredList(GetDataAdapter1);
                    recyclerViewadapter.notifyDataSetChanged();


                }
                return true;
            }
        });
    }

    public void recyclerWithHeader(View view){

        restaurant_recycler_view = view.findViewById(R.id.restaurant_recycler_view);
        //progressBar = view.findViewById(R.id.restaurantProgress);
//        restaurant_recycler_view.setHasFixedSize(true);

        recyclerViewlayoutManager = new LinearLayoutManager(getContext());

        restaurant_recycler_view.setLayoutManager(recyclerViewlayoutManager);
/*
       int space = (int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, 8,
                getResources().getDisplayMetrics());*/
      /*  restaurant_recycler_view.addItemDecoration(new DividerItemDecoration(getActivity(),
                DividerItemDecoration.VERTICAL));
        restaurant_recycler_view.addItemDecoration(new SpacesItemDecoration(0));*/


        view = view.findViewById(R.id.layout);

    }

    @Override
    public void onConnectionFailed(@NonNull ConnectionResult connectionResult) {
      //  Toast.makeText(getContext(),"On Failed",Toast.LENGTH_SHORT).show();
    }

    @Override
    public void onStart() {
        super.onStart();
        LocalBroadcastManager.getInstance(getContext()).registerReceiver((mMessageReceiver),
                new IntentFilter("MyData")
        );

    }



    @Override
    public void onStop() {
        super.onStop();
        handler.removeCallbacks(timeCounter);
        LocalBroadcastManager.getInstance(getContext()).unregisterReceiver(mMessageReceiver);
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        handler.removeCallbacks(timeCounter);
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        handler.removeCallbacks(timeCounter);
    }

    @Override
    public void onPause() {
        super.onPause();

        handler.removeCallbacks(timeCounter);
        LocalBroadcastManager.getInstance(getContext()).registerReceiver((mMessageReceiver),
                new IntentFilter("MyData")
        );

    }

    @Override
    public void onResume() {
        super.onResume();
      //  getRestaurantList();
      //  recyclerViewadapter.notifyDataSetChanged();

        if(MapsActivity.SAVE_LOCATION) {

            lat = sharedPreferences.getString(PreferenceClass.LATITUDE, "");
            lon = sharedPreferences.getString(PreferenceClass.LONGITUDE, "");

            Address locationAddress;

            locationAddress = getAddress(Double.parseDouble(lat), Double.parseDouble(lon));
            if (locationAddress != null) {

                String city = locationAddress.getLocality();

                String country = locationAddress.getCountryName();

                String address = city + " " + country;

                title_city_tv.setText(address);

                SharedPreferences.Editor editor = sharedPreferences.edit();
                editor.putString(PreferenceClass.CURRENT_LOCATION_ADDRESS, address).commit();
                getRestaurantList();
                MapsActivity.SAVE_LOCATION = false;

                //  JSON_DATA_WEB_CALL();

                // getRestList();
            }
        }


    }



    @Override
    public void onDetach() {
        super.onDetach();

    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {

       /* if (requestCode == PLACE_PICKER_REQUEST) {
            if (resultCode == getActivity().RESULT_OK) {
                Place place = PlacePicker.getPlace(data, getContext());
                StringBuilder stBuilder = new StringBuilder();
                String placename = String.format("%s", place.getName());
                latitude = String.valueOf(place.getLatLng().latitude);
                longitude = String.valueOf(place.getLatLng().longitude);
                String address = String.format("%s", place.getAddress());
                stBuilder.append("Name: ");
                stBuilder.append(placename);
                stBuilder.append("\n");
                stBuilder.append("Latitude: ");
                stBuilder.append(latitude);
                stBuilder.append("\n");
                stBuilder.append("Logitude: ");
                stBuilder.append(longitude);
                stBuilder.append("\n");
                stBuilder.append("Address: ");
                stBuilder.append(address);


            }
        }*/
    }


    public Address getAddress(double latitude, double longitude)
    {
        Geocoder geocoder;
        List<Address> addresses;
        geocoder = new Geocoder(getContext(), Locale.getDefault());

        try {
            addresses = geocoder.getFromLocation(latitude,longitude, 1); // Here 1 represent max location result to returned, by documents it recommended 1 to 5
            return addresses.get(0);

        } catch (IOException e) {
            e.printStackTrace();
        }

        return null;

    }

    @Override
    public void onConnected(@Nullable Bundle bundle) {

    }

    @Override
    public void onConnectionSuspended(int i) {

    }


    private BroadcastReceiver mMessageReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {


           /* Intent intenttwo = new Intent(context, RestReveiwActivity.class);
            // intentone.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
            startActivity(intenttwo);*/

          /*  Intent intentone = new Intent(context, RiderReviewActivity.class);
            // intentone.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
            startActivity(intentone);

            Intent intentone = new Intent(context, RestReveiwActivity.class);
            // intentone.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
            startActivity(intentone);

          String type = intent.getExtras().getString("type");
            if(type.equalsIgnoreCase("rider_review")){

                Intent intentone = new Intent(context, RiderReviewActivity.class);
                // intentone.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                startActivity(intentone);


            }
            else {


                Intent intentone = new Intent(context, RestReveiwActivity.class);
                // intentone.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                startActivity(intentone);


            }*/
        }
    };



    public class RestaurantsAdapter extends RecyclerView.Adapter<RestaurantsAdapter.ViewHolder> implements Filterable {

        ArrayList<RestaurantsModel> getDataAdapter;
        private ArrayList<RestaurantsModel> mFilteredList;
        Context context;
        ImageLoader imageLoader1;
        OnItemClickListner onItemClickListner;
        SharedPreferences sharedPreferences;


        public RestaurantsAdapter(ArrayList<RestaurantsModel> getDataAdapter, Context context){
            super();
            this.getDataAdapter = getDataAdapter;
            this.mFilteredList = getDataAdapter;
            this.context = context;
        }

        public ArrayList<RestaurantsModel> getmFilteredList() {
            return mFilteredList;
        }

        public void setmFilteredList(ArrayList<RestaurantsModel> mFilteredList) {
            this.mFilteredList = mFilteredList;
        }

        @Override
        public RestaurantsAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
            View v = null;
            v = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_items_restaurants2, parent, false);
            ViewHolder viewHolder = new ViewHolder(v);

            return viewHolder;
        }

        @Override
        public void onBindViewHolder(final RestaurantsAdapter.ViewHolder holder, final int position) {

            final RestaurantsModel getDataAdapter1 =  getmFilteredList().get(position);

            sharedPreferences = context.getSharedPreferences(PreferenceClass.user,Context.MODE_PRIVATE);
            imageLoader1 = ServerImageParseAdapter.getInstance(context).getImageLoader();


//        imageLoader1.get(getDataAdapter1.getRestaurant_image(),
            //ImageLoader.getImageListener(
            //      holder.restaurant_img,//Server Image
            //      R.mipmap.ic_launcher,//Before loading server image the default showing image.
            //    android.R.drawable.ic_dialog_alert  );  //Error image if requested image dose not found on server.

            Picasso.with(context).load(Config.imgBaseURL+getDataAdapter1.getRestaurant_image()).
                    fit().centerCrop()
                    .placeholder(R.drawable.unknown_img)
                    .error(R.drawable.unknown_img).into(holder.restaurant_img);

            holder.title_restaurants.setText(getDataAdapter1.getRestaurant_name().trim());

            //}

            String symbol = getDataAdapter1.getRestaurant_currency();
            holder.salogon_restaurants.setText(getDataAdapter1.getRestaurant_salgon().trim());
            holder.baked_time_tv.setText(getDataAdapter1.getPreparation_time()+ " min");
             //  holder.item_price_per_mile.setText(symbol+" "+getDataAdapter1.getDelivery_fee_per_km()+" / over"+" "+symbol+" "+getDataAdapter1.getMin_order_price());

            holder.ratingBar.setRating(Float.parseFloat(getDataAdapter1.getRestaurant_avgRating()));

            if(getDataAdapter1.getMin_order_price().equalsIgnoreCase("0.00")){
                holder.item_time_tv.setText(symbol + " " + getDataAdapter1.getDelivery_fee_per_km() + " /km");
            }
            else {
                holder.item_time_tv.setText(symbol + " " + getDataAdapter1.getDelivery_fee_per_km() + " /km- Free over" + " " + symbol + " " + getDataAdapter1.getMin_order_price());

            }
            String getFavoriteStatus = getDataAdapter1.getRestaurant_isFav();


            String getPromotedString = getDataAdapter1.getPromoted();

            if (getPromotedString.equalsIgnoreCase("1"))
            {
                holder.featured.setVisibility(View.VISIBLE);
            }
            else {
                holder.featured.setVisibility(View.GONE);
            }


            holder.restaurant_row_main.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {

                    if (onItemClickListner !=null){
                        int position = holder.getAdapterPosition();
                        String name = mFilteredList.get(position).getRestaurant_id();
                        for (int i=0 ; i <getDataAdapter.size() ; i++ ){
                            if(name.equals(getDataAdapter.get(i).getRestaurant_id())){
                                position = i;
                                break;
                            }
                        }
                        if (position != RecyclerView.NO_POSITION) {
                            onItemClickListner.OnItemClicked(view,position);
                        }
                    }
                }
            });




        }

        @Override
        public int getItemViewType(int position) {
            if (position == 0)
                return 1;
            else
                return 2;
        }

        @Override
        public int getItemCount() {
            return mFilteredList.size() ;
        }
        @SuppressWarnings("unchecked")
        @Override
        public Filter getFilter() {

            return new Filter() {
                @Override
                protected FilterResults performFiltering(CharSequence charSequence) {
                    String charString = charSequence.toString();
                    if (charString.isEmpty()) {
                        mFilteredList = getDataAdapter;
                    } else {
                        ArrayList<RestaurantsModel> filteredList = new ArrayList<>();
                        for (RestaurantsModel row : getDataAdapter) {

                            // name match condition. this might differ depending on your requirement
                            // here we are looking for name or phone number match
                            if (row.getRestaurant_name().toLowerCase().contains(charString.toLowerCase())) {
                                filteredList.add(row);
                            }
                        }

                        mFilteredList = filteredList;
                    }

                    FilterResults filterResults = new FilterResults();
                    filterResults.values = mFilteredList;
                    return filterResults;
                }

                @Override
                protected void publishResults(CharSequence charSequence, FilterResults filterResults) {
                    mFilteredList = (ArrayList<RestaurantsModel>) filterResults.values;
                    notifyDataSetChanged();
                }
            };

        }

        class ViewHolder extends RecyclerView.ViewHolder{

            public TextView title_restaurants,salogon_restaurants,item_price_tv,item_time_tv,baked_time_tv;
            public ImageView restaurant_img;
            public RelativeLayout restaurant_row_main;
            public RatingBar ratingBar;
            public ImageView featured;

            public ViewHolder(View itemView) {

                super(itemView);
                title_restaurants = (TextView)itemView.findViewById(R.id.title_restaurants);
                salogon_restaurants = (TextView)itemView.findViewById(R.id.salogon_restaurants);
                // item_price_per_mile = itemView.findViewById(R.id.item_price_per_mile);
                item_price_tv = itemView.findViewById(R.id.item_price_tv);

                restaurant_img =  itemView.findViewById(R.id.profile_image_restaurant) ;
                restaurant_row_main = itemView.findViewById(R.id.restaurant_row_main);
                ratingBar = itemView.findViewById(R.id.ruleRatingBar);
                featured = itemView.findViewById(R.id.featured);
                item_time_tv = itemView.findViewById(R.id.item_time_tv);
                baked_time_tv = itemView.findViewById(R.id.baked_time_tv);

            }
        }


        public void setOnItemClickListner(OnItemClickListner onCardClickListner) {
            this.onItemClickListner = onCardClickListner;
        }




        public void addFavoriteRestaurant(String res_id){
            TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
            transparent_layer.setVisibility(View.VISIBLE);
            progressDialog.setVisibility(View.VISIBLE);

            String user_id = sharedPreferences.getString(PreferenceClass.pre_user_id,"");
            RequestQueue queue = Volley.newRequestQueue(context);

            JSONObject jsonObject = new JSONObject();

            try {
                jsonObject.put("user_id",user_id);
                jsonObject.put("restaurant_id",res_id);
                jsonObject.put("favourite","1");

            } catch (JSONException e) {
                e.printStackTrace();
            }

            JsonObjectRequest favJsonRequest = new JsonObjectRequest(Request.Method.POST, Config.ADD_FAV_RESTAURANT, jsonObject, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {

                    String resposeStr = response.toString();
                    JSONObject converResponseToJson = null;

                    try {
                        converResponseToJson = new JSONObject(resposeStr);

                        int code_id  = Integer.parseInt(converResponseToJson.optString("code"));
                        if(code_id == 200) {

                       /* ArrayList<RestaurantsModel> tempModel=new ArrayList<RestaurantsModel>();
                        tempModel.clear();
                        tempModel.addAll(tempModel);
                        progressBar.setVisibility(View.GONE);*/
                            TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                            transparent_layer.setVisibility(View.GONE);
                            progressDialog.setVisibility(View.GONE);
                            getRestaurantList();
                            notifyDataSetChanged();
                           // getRestList();

                  /*      new Handler(Looper.getMainLooper()).post(new Runnable() {
                            public void run() {
                                progressBar.setVisibility(View.GONE);
                                notifyDataSetChanged();
                            }
                        });*/
                            // Toast.makeText(getApplicationContext(),converResponseToJson.optString("msg"),Toast.LENGTH_LONG).show();


                        }
                        else {
                            TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                            transparent_layer.setVisibility(View.GONE);
                            progressDialog.setVisibility(View.GONE);
                        }


                    } catch (JSONException e) {
                        e.printStackTrace();
                    }


                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                    transparent_layer.setVisibility(View.GONE);
                    progressDialog.setVisibility(View.GONE);
                    VolleyLog.d("JSONPost", "Error: " + error.getMessage());
                 //   Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show();
                }
            }){
                @Override
                public String getBodyContentType() {
                    return "application/json; charset=utf-8";
                }

                @Override
                public Map<String, String> getHeaders() throws AuthFailureError {
                    HashMap<String, String> headers = new HashMap<String, String>();
                    headers.put("api-key", "2a5588cf-4cf3-4f1c-9548-cc1db4b54ae3");
                    return headers;
                }
            };

            queue.add(favJsonRequest);
        }


    }

    public interface OnItemClickListner {
        void OnItemClicked(View view, int position);
    }

}
