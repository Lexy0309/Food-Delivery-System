package com.dinosoftlabs.foodies.android.HActivitiesAndFragment;


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
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.gmail.samehadar.iosdialog.CamomileSpinner;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.OrderDetailFragment;
import com.dinosoftlabs.foodies.android.Adapters.OrderAdapter;
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.Models.OrderModelClass;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

/**
 * Created by Nabeel on 2/15/2018.
 */

public class HOrderHistory extends Fragment {

    SharedPreferences sPre;
    RelativeLayout order_type_div;

    RecyclerView order_history_recyclerview;

    RecyclerView.LayoutManager recyclerViewlayoutManager;
    OrderAdapter recyclerViewadapter;

    LinearLayout recycler_view_restaurant;
    CamomileSpinner orderProgressBar;
    SwipeRefreshLayout refresh_layout;
   // String PendingOrders = "PendingOrders";
  //  String AcceptedOrders = "AcceptedOrders";

    ArrayList<OrderModelClass> orderArrayList;
    TextView order_tv,title_tv;

    public static boolean HOTEL_ORDER_HISTORY,FLAG_HOTEL_ORDER_HISTORY;
    @SuppressWarnings("deprecation")
    PercentRelativeLayout no_job_div;
    ImageView filter_search;

    RelativeLayout progressDialog;
    RelativeLayout transparent_layer;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        getActivity().getWindow().setSoftInputMode(
                WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_HIDDEN);
        getActivity().getWindow().setSoftInputMode(
                WindowManager.LayoutParams.SOFT_INPUT_STATE_VISIBLE|WindowManager.LayoutParams.SOFT_INPUT_ADJUST_RESIZE);
        View view = inflater.inflate(R.layout.orders_fragment, container, false);
        FrameLayout frameLayout = view.findViewById(R.id.order_fragment_container);
        FontHelper.applyFont(getContext(),frameLayout, AllConstants.verdana);
        frameLayout.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                return true;
            }
        });

        sPre = getContext().getSharedPreferences(PreferenceClass.user,getContext().MODE_PRIVATE);

        transparent_layer = view.findViewById(R.id.transparent_layer);
        order_history_recyclerview = view.findViewById(R.id.order_history_recyclerview);
        orderProgressBar = view.findViewById(R.id.orderProgress);
        orderProgressBar.start();

        progressDialog = view.findViewById(R.id.progressDialog);
        filter_search = view.findViewById(R.id.filter_search);
        order_type_div = view.findViewById(R.id.order_type_div);
        order_history_recyclerview.setHasFixedSize(true);
        recyclerViewlayoutManager = new LinearLayoutManager(getContext());
        order_history_recyclerview.setLayoutManager(recyclerViewlayoutManager);

        FLAG_HOTEL_ORDER_HISTORY = true;

        if(!HOTEL_ORDER_HISTORY){
            filter_search.setVisibility(View.GONE);
            order_type_div.setVisibility(View.GONE);
        }

        initUI(view);
        getAllOrderParser();

        return view;
    }

    private void initUI(View v){
        title_tv = v.findViewById(R.id.title_tv);
        title_tv.setText(getResources().getString(R.string.history));
        order_tv = v.findViewById(R.id.order_tv);
        recycler_view_restaurant = v.findViewById(R.id.recycler_view_restaurant );
        no_job_div = v.findViewById(R.id.no_job_div);
        refresh_layout = v.findViewById(R.id.refresh_layout);
        refresh_layout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {

                getAllOrderParser();
                refresh_layout.setRefreshing(false);

            }
        });


    }

    @Override
    public void onAttachFragment(Fragment childFragment) {
        super.onAttachFragment(childFragment);
      //  setUserVisibleHint(false);
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
      //  setUserVisibleHint(false);
       // FLAG_ORDER_HOTEL = true;

    }

    @Override
    public void setMenuVisibility(boolean menuVisible) {
        super.setMenuVisibility(menuVisible);

    }

    private void getAllOrderParser(){
        transparent_layer.setVisibility(View.VISIBLE);
        orderProgressBar.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);
        orderArrayList = new ArrayList<>();
        String user_id = sPre.getString(PreferenceClass.pre_user_id,"");
        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MMM-dd hh:mm:ss");
        String currentDateandTime = sdf.format(new Date());

        RequestQueue queue = Volley.newRequestQueue(getContext());

        JSONObject jsonObject = new JSONObject();
        try {

            jsonObject.put("user_id",user_id);
            jsonObject.put("datetime",currentDateandTime);

            Log.e("Obj",jsonObject.toString());

        } catch (JSONException e) {
            e.printStackTrace();
        }
        // Request a string response from the provided URL.
        JsonObjectRequest jsonObjReq = new JsonObjectRequest(Request.Method.POST,
                Config.SHOW_REST_COMPLETE_ORDER,jsonObject,
                new Response.Listener<JSONObject>() {

                    @Override
                    public void onResponse(JSONObject response) {

                        Log.d("JSONPost", response.toString());
                        String strJson =  response.toString();
                        JSONObject jsonResponse = null;
                        try {
                            jsonResponse = new JSONObject(strJson);

                            Log.d("JSONPost", jsonResponse.toString());

                            int code_id  = Integer.parseInt(jsonResponse.optString("code"));

                            if(code_id == 200) {

                                JSONObject json = new JSONObject(jsonResponse.toString());
                                JSONArray jsonarray = json.getJSONArray("msg");

                                Log.d("Length",String.valueOf(jsonarray.length()));

                                if(jsonarray.length()==0){
                                    recycler_view_restaurant.setVisibility(View.GONE);
                                }
                                else {
                                    recycler_view_restaurant.setVisibility(View.VISIBLE);
                                }

                                for (int i = 0; i < jsonarray.length(); i++) {

                                    OrderModelClass orderModelClass = new OrderModelClass();
                                    JSONObject json1 = jsonarray.getJSONObject(i);

                                    JSONObject jsonObjOrder = json1.getJSONObject("Order");
                                    JSONObject jsonObjCurrency = jsonObjOrder.getJSONObject("Currency");
                                    JSONArray jsonarrayOrder = jsonObjOrder.getJSONArray("OrderMenuItem");

                                    JSONObject jsonObjectMenu = jsonarrayOrder.getJSONObject(0);
                                    JSONObject jsonObjectExtraMenu = null;
                                    if(jsonObjectMenu.getJSONArray("OrderMenuExtraItem")!=null && jsonObjectMenu.getJSONArray("OrderMenuExtraItem").length()>0) {
                                        JSONArray jsonarrayExtraOrder = jsonObjectMenu.getJSONArray("OrderMenuExtraItem");
                                        jsonObjectExtraMenu = jsonarrayExtraOrder.getJSONObject(0);
                                        orderModelClass.setOrder_extra_item_name(jsonObjectExtraMenu.optString("name"));
                                    }


                                    orderModelClass.setCurrency_symbol(jsonObjCurrency.optString("symbol"));
                                    orderModelClass.setOrder_created(jsonObjOrder.optString("created"));

                                    orderModelClass.setOrder_id(jsonObjOrder.optString("id"));
                                    orderModelClass.setOrder_menu_id(jsonObjectMenu.optString("id"));
                                    orderModelClass.setOrder_name(jsonObjectMenu.optString("name"));
                                    orderModelClass.setOrder_price(jsonObjOrder.optString("price"));
                                    orderModelClass.setInstructions(jsonObjOrder.optString("instructions"));
                                    orderModelClass.setRestaurant_name(jsonObjOrder.optString("name"));
                                    orderModelClass.setOrder_quantity(jsonObjOrder.optString("quantity"));
                                    orderModelClass.setDelivery(jsonObjOrder.optString("delivery"));
                                    orderModelClass.setDeal_id(jsonObjOrder.optString("deal_id"));

                                    orderArrayList.add(orderModelClass);
                                    // Toast.makeText(getContext(),orderModelClassArrayList.toString(),Toast.LENGTH_SHORT).show();

                                }

                                if (orderArrayList!=null) {

                                    if(orderArrayList.size()>0){
                                        no_job_div.setVisibility(View.GONE);

                                    }
                                    else if(orderArrayList.size()==0) {
                                        no_job_div.setVisibility(View.VISIBLE);
                                    }

                                    orderProgressBar.setVisibility(View.GONE);
                                    progressDialog.setVisibility(View.GONE);
                                    transparent_layer.setVisibility(View.GONE);
                                    recyclerViewadapter = new OrderAdapter(orderArrayList, getActivity());
                                    order_history_recyclerview.setAdapter(recyclerViewadapter);
                                    recyclerViewadapter.notifyDataSetChanged();

                                }
                                recyclerViewadapter.setOnItemClickListner(new OrderAdapter.OnItemClickListner() {
                                    @Override
                                    public void OnItemClicked(View view, int position) {

                                        SharedPreferences.Editor editor = sPre.edit();
                                        editor.putString(PreferenceClass.ORDER_HEADER,orderArrayList.get(position).getOrder_name());
                                        editor.putString(PreferenceClass.ORDER_ID,orderArrayList.get(position).getOrder_id());
                                        editor.putString(PreferenceClass.ORDER_INS,orderArrayList.get(position).getInstructions());
                                        editor.commit();

                                        HOTEL_ORDER_HISTORY = true;

                                        Fragment restaurantMenuItemsFragment = new OrderDetailFragment();
                                        FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                                        transaction.add(R.id.order_fragment_container, restaurantMenuItemsFragment,"ParentFragment").commit();

                                    }
                                });

                            }else{
                                no_job_div.setVisibility(View.VISIBLE);
                                orderProgressBar.setVisibility(View.GONE);
                                progressDialog.setVisibility(View.GONE);
                                transparent_layer.setVisibility(View.GONE);
                                JSONObject json = new JSONObject(jsonResponse.toString());
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
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
             //   Toast.makeText(getContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
                orderProgressBar.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);
                transparent_layer.setVisibility(View.GONE);
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

}

