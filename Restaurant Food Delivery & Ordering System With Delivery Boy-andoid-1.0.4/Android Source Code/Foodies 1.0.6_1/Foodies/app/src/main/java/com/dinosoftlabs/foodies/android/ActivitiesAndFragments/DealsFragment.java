package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.content.Context;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.annotation.Nullable;

import android.support.percent.PercentRelativeLayout;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.dinosoftlabs.foodies.android.Adapters.DealsAdapter;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.Models.DealsModel;

import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.TabLayoutUtils;
import com.gmail.samehadar.iosdialog.CamomileSpinner;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;


/**
 * Created by Nabeel on 12/12/2017.
 */

public class DealsFragment extends Fragment {
    ImageView cart_icon;
    public static boolean FLAG_CART_DEALS_FRAGMENT;
    private RecyclerView deals_recyclerview;
    RecyclerView.LayoutManager recyclerViewlayoutManager;
    DealsAdapter recyclerViewadapter;
    CamomileSpinner dealsProgressBar;
    SwipeRefreshLayout mSwipeRefreshLayout;
    ArrayList<DealsModel> delsArrayList;
    public static boolean FLAG_DEAL_FRAGMENT,DEAL;
    SharedPreferences dealsSharedPreferences;
    @SuppressWarnings("deprecation")
    PercentRelativeLayout no_job_div;
    ImageView back_icon;
    RelativeLayout transparent_layer,progressDialog;
    public static boolean OPEN_DEALS;

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        dealsSharedPreferences = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);

    }

    @Override
    public void setUserVisibleHint(boolean isVisibleToUser) {
        super.setUserVisibleHint(isVisibleToUser);
        if(isVisibleToUser){

        }

    }
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.deals_fragment, container, false);

        progressDialog = view.findViewById(R.id.progressDialog);
        transparent_layer = view.findViewById(R.id.transparent_layer);

        deals_recyclerview = view.findViewById(R.id.deals_recyclerview);
        dealsProgressBar = view.findViewById(R.id.dealsProgress);
        dealsProgressBar.start();
        deals_recyclerview.setHasFixedSize(true);

        recyclerViewlayoutManager = new LinearLayoutManager(getContext());
        deals_recyclerview.setLayoutManager(recyclerViewlayoutManager);


     /*   if(CartReportFragment.FLAG_DEAL_REAL_DATA){


        }*/


        initUI(view);
        getDealsList();
        return view;
    }

    private void initUI(View v){
        back_icon = v.findViewById(R.id.back_icon);

        no_job_div = v.findViewById(R.id.no_job_div);

        mSwipeRefreshLayout = v.findViewById(R.id.refresh_layout);
        mSwipeRefreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
               getDealsList();


                mSwipeRefreshLayout.setRefreshing(false);
            }
        });
      /*  cart_icon = v.findViewById(R.id.order_place);
        cart_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new CartReportFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.dearls_frag_main_container, restaurantMenuItemsFragment,"ParentFragment").commit();
                FLAG_CART_DEALS_FRAGMENT = true;
            }
        });*/

    }

    @Override
    public void setMenuVisibility(boolean menuVisible) {
        super.setMenuVisibility(menuVisible);

    }

    @Override
    public void onStart() {
        super.onStart();

    }

    private void getDealsList(){
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);
        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MMM-dd hh:mm:ss");
        String currentDateandTime = sdf.format(new Date());
        delsArrayList = new ArrayList<>();
        RequestQueue queue = Volley.newRequestQueue(getContext());
        String lat = dealsSharedPreferences.getString(PreferenceClass.LATITUDE,"");
        String long_ = dealsSharedPreferences.getString(PreferenceClass.LONGITUDE,"");

        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("lat", lat);
            jsonObject.put("long", long_);
            jsonObject.put("current_time",currentDateandTime);

            //jsonObject.put("lat", latitude);
            //jsonObject.put("long", longitude);

            Log.e("Obj",jsonObject.toString());

        } catch (JSONException e) {
            e.printStackTrace();
        }


        Log.d("JSONPost", jsonObject.toString());
        Log.d("JSONPost", Config.SHOW_DEALS);

// Request a string response from the provided URL.
        JsonObjectRequest jsonObjReq = new JsonObjectRequest(Request.Method.POST,
                Config.SHOW_DEALS,jsonObject,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            int code_id  = Integer.parseInt(response.optString("code"));
                            if(code_id == 200) {
                                JSONArray jsonarray = response.getJSONArray("msg");
                                for (int i = 0; i < jsonarray.length(); i++) {

                                    JSONObject json1 = jsonarray.getJSONObject(i);

                                    JSONObject jsonObjDeal = json1.getJSONObject("Deal");
                                    JSONObject jsonObjRestaurant = json1.getJSONObject("Restaurant");
                                    JSONObject jsonObjCurrency = jsonObjRestaurant.getJSONObject("Currency");
                                    JSONObject jsonObjTax = jsonObjRestaurant.getJSONObject("Tax");

                                    DealsModel dealsModel = new DealsModel();
                                    dealsModel.setPromoted(jsonObjDeal.optString("promoted"));
                                    dealsModel.setDeal_cover_image(jsonObjDeal.optString("cover_image"));
                                    dealsModel.setDeal_image(jsonObjDeal.optString("image"));
                                    dealsModel.setDeal_desc(jsonObjDeal.optString("description"));
                                    dealsModel.setDeal_restaurant_id(jsonObjDeal.optString("restaurant_id"));
                                    dealsModel.setDeal_id(jsonObjDeal.optString("id"));
                                    dealsModel.setDeal_name(jsonObjDeal.optString("name"));
                                    dealsModel.setDeal_price(jsonObjDeal.optString("price"));
                                    dealsModel.setDeal_expiry_date(jsonObjDeal.optString("ending_time"));

                                    dealsModel.setDeal_symbol(jsonObjCurrency.optString("symbol"));
                                    dealsModel.setRestaurant_name(jsonObjRestaurant.optString("name"));
                                    dealsModel.setDeal_tax(jsonObjTax.optString("tax"));
                                    dealsModel.setDeal_delivery_fee(jsonObjTax.optString("delivery_fee_per_km"));
                                    dealsModel.setIsDeliveryFree(jsonObjRestaurant.optString("tax_free"));



                                    delsArrayList.add(dealsModel);

                                }

                                if(delsArrayList!=null) {

                                    if(delsArrayList.isEmpty()){
                                        no_job_div.setVisibility(View.VISIBLE);
                                    }
                                    else {
                                        no_job_div.setVisibility(View.GONE);
                                    }

                                    recyclerViewadapter = new DealsAdapter(delsArrayList, getActivity());
                                    deals_recyclerview.setAdapter(recyclerViewadapter);
                                    recyclerViewadapter.notifyDataSetChanged();
                                    transparent_layer.setVisibility(View.GONE);
                                    progressDialog.setVisibility(View.GONE);
                                    TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                                    recyclerViewadapter.setOnItemClickListner(new DealsAdapter.OnItemClickListner() {
                                        @Override
                                        public void OnItemClicked(View view, int position) {


                                            SharedPreferences.Editor editor = dealsSharedPreferences.edit();
                                            editor.putString(PreferenceClass.DEALS_DESC, delsArrayList.get(position).getDeal_desc());
                                            editor.putString(PreferenceClass.DEALS_HOTEL_NAME, delsArrayList.get(position).getRestaurant_name());
                                            editor.putString(PreferenceClass.DELAS_NAME, delsArrayList.get(position).getDeal_name());
                                            editor.putString(PreferenceClass.DEALS_PRICE, delsArrayList.get(position).getDeal_price());
                                            editor.putString(PreferenceClass.DEALS_IMAGE, delsArrayList.get(position).getDeal_image());
                                            editor.putString(PreferenceClass.DEALS_CURRENCY_SYMBOL, delsArrayList.get(position).getDeal_symbol());
                                            editor.putString(PreferenceClass.DEALS_TAX, delsArrayList.get(position).getDeal_tax());
                                            editor.putString(PreferenceClass.RESTAURANT_NAME,delsArrayList.get(position).getRestaurant_name());
                                            editor.putString(PreferenceClass.DEALS_DELIVERY_FEE, delsArrayList.get(position).getDeal_delivery_fee());
                                            editor.putString(PreferenceClass.DEAL_ID,delsArrayList.get(position).getDeal_id());
                                            editor.putString(PreferenceClass.IS_DELIVERY_FREE,delsArrayList.get(position).getIsDeliveryFree());
                                            editor.putString(PreferenceClass.RESTAURANT_ID,delsArrayList.get(position).getDeal_restaurant_id());
                                            editor.commit();

                                            Fragment restaurantMenuItemsFragment = new DealsDetailFragment();
                                            FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                                            transaction.add(R.id.dearls_frag_main_container, restaurantMenuItemsFragment, "parent").commit();
                                            OPEN_DEALS = true;

                                        }
                                    });

                                }


                            }else{
                                JSONObject json = new JSONObject(response.toString());
                             //   Toast.makeText(getContext(),json.optString("msg"), Toast.LENGTH_SHORT).show();
                            }

                            //JSONArray jsonMainNode = jsonResponse.optJSONArray("msg");                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }


                        //pDialog.hide();
                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                //  ed_progress.setVisibility(View.GONE);
                VolleyLog.d("JSONPost_DealFragment", "Error: " + error.getMessage());
              //  Toast.makeText(getContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
            }
        }){
//            @Override
//            public String getBodyContentType() {
//                return "application/json; charset=utf-8";
//            }

            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                HashMap<String, String> headers = new HashMap<String, String>();
                headers.put("api-key", "2a5588cf-4cf3-4f1c-9548-cc1db4b54ae3");
                return headers;
            }
        };

// Add the request to the RequestQueue.
        queue.add(jsonObjReq);

    }

    @Override
    public void onResume() {
        super.onResume();
    }
}
