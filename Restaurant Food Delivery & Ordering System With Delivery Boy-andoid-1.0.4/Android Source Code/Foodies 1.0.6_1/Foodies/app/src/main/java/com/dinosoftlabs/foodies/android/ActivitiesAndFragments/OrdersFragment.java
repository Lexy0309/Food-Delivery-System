package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.app.Dialog;
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
import com.dinosoftlabs.foodies.android.Adapters.OrderAdapter;
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.Models.OrderModelClass;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;
import com.dinosoftlabs.foodies.android.Utils.TabLayoutUtils;
import com.gmail.samehadar.iosdialog.CamomileSpinner;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import static com.dinosoftlabs.foodies.android.ActivitiesAndFragments.DealOrderFragment.DEAL_PLACED;

/**
 * Created by Nabeel on 12/12/2017.
 */

public class OrdersFragment extends Fragment {

    ImageView filter_search;
    public static boolean FLAG_CART_ORDER_FRAGMENT,STATUS_INACTIVE,FLAG_ACCEPTED_ORDER;

    SharedPreferences sPre;

    RecyclerView order_history_recyclerview;

    RecyclerView.LayoutManager recyclerViewlayoutManager;
    OrderAdapter recyclerViewadapter;

    LinearLayout recycler_view_restaurant;
    CamomileSpinner orderProgressBar;
    SwipeRefreshLayout refresh_layout;
    String status_active = "1";
    String status_inactive = "2";

    ArrayList<OrderModelClass> orderArrayList;
    @SuppressWarnings("deprecation")
    PercentRelativeLayout no_job_div;
    TextView title_tv;
    RelativeLayout transparent_layer,progressDialog;
  //  RelativeLayout deals_div,order_div;
  //  TextView deal_tv_btn,order_tv_btn;

    public static boolean _hasLoadedOnce= false;
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.orders_fragment, container, false);
        FrameLayout frameLayout = view.findViewById(R.id.order_fragment_container);
        FontHelper.applyFont(getContext(),frameLayout, AllConstants.verdana);
        frameLayout.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                return true;
            }
        });

        initUI(view);
        sPre = getContext().getSharedPreferences(PreferenceClass.user,getContext().MODE_PRIVATE);
        FLAG_ACCEPTED_ORDER = true;
        order_history_recyclerview = view.findViewById(R.id.order_history_recyclerview);
        orderProgressBar = view.findViewById(R.id.orderProgress);
        orderProgressBar.start();

        order_history_recyclerview.setHasFixedSize(true);
        recyclerViewlayoutManager = new LinearLayoutManager(getContext());
        order_history_recyclerview.setLayoutManager(recyclerViewlayoutManager);

        if( OrderDetailFragment.CALLBACK_ORDERFRAG||DEAL_PLACED){
            getAllOrderParser(status_active);
            OrderDetailFragment.CALLBACK_ORDERFRAG = false;
            DEAL_PLACED= false;
        }

        return view;
    }

    @Override
    public void setUserVisibleHint(boolean isFragmentVisible_) {
        super.setUserVisibleHint(true);

        if (this.isVisible()) {
            // we check that the fragment is becoming visible
            if (isFragmentVisible_ && !_hasLoadedOnce) {
                getAllOrderParser(status_active);
                _hasLoadedOnce = true;
            }
        }
    }

    private void initUI(View v){


        progressDialog = v.findViewById(R.id.progressDialog);
        transparent_layer = v.findViewById(R.id.transparent_layer);

        title_tv = v.findViewById(R.id.title_tv);
        title_tv.setText(getResources().getString(R.string.history));
        //order_tv_btn = v.findViewById(R.id.order_tv_btn);
       // deal_tv_btn = v.findViewById(R.id.deal_tv_btn);
       // order_div = v.findViewById(R.id.order_div);
       // deals_div = v.findViewById(R.id.deals_div);
        no_job_div = v.findViewById(R.id.no_job_div);
        refresh_layout = v.findViewById(R.id.refresh_layout);
        refresh_layout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {

                if(FLAG_ACCEPTED_ORDER) {
                    getAllOrderParser(status_active);
                }
                else {
                    getAllOrderParser(status_inactive);
                }
                refresh_layout.setRefreshing(false);

            }
        });

       recycler_view_restaurant = v.findViewById(R.id.recycler_view_restaurant );
                filter_search = v.findViewById(R.id.filter_search);
        filter_search.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                customDialogbox();
            }
        });

     /*   deals_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                deals_div.setBackground(getResources().getDrawable(R.drawable.round_shape_btn_white_left));
                order_div.setBackground(getResources().getDrawable(R.drawable.round_shape_btn_red_right));
                order_tv_btn.setTextColor(getResources().getColor(R.color.colorWhite));
                deal_tv_btn.setTextColor(getResources().getColor(R.color.colorRed));

                FLAG_DEALS = true;

                getAllOrderParser(status_active);

              //  decline_tv.setTextColor(getResources().getColor(R.color.colorWhite));
              //  accept_tv.setTextColor(getResources().getColor(R.color.or_color_name));
            }
        });
        order_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                order_div.setBackground(getResources().getDrawable(R.drawable.round_shape_btn_white));
                deals_div.setBackground(getResources().getDrawable(R.drawable.round_shape_btn_red));
                order_tv_btn.setTextColor(getResources().getColor(R.color.colorRed));
                deal_tv_btn.setTextColor(getResources().getColor(R.color.colorWhite));
                FLAG_DEALS = false;
                getAllOrderParser(status_active);
               // decline_tv.setTextColor(getResources().getColor(R.color.or_color_name));
            //   accept_tv.setTextColor(getResources().getColor(R.color.colorWhite));
            }
        });*/

    }

    @Override
    public void onAttachFragment(Fragment childFragment) {
        super.onAttachFragment(childFragment);
        setUserVisibleHint(false);
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setUserVisibleHint(false);
    }

    @Override
    public void setMenuVisibility(boolean menuVisible) {
        super.setMenuVisibility(menuVisible);


       /* if(menuVisible){
            recyclerViewadapter = new OrderAdapter(MainActivity.orderArrayList, getActivity());
            order_history_recyclerview.setAdapter(recyclerViewadapter);
            recyclerViewadapter.notifyDataSetChanged();
            orderProgressBar.setVisibility(View.GONE);

        }*/
    }

    private void getAllOrderParser(String status_){
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);
        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        orderArrayList = new ArrayList<>();
       String user_id = sPre.getString(PreferenceClass.pre_user_id,"0");
        RequestQueue queue = Volley.newRequestQueue(getContext());

        JSONObject jsonObject = new JSONObject();
        try {

            jsonObject.put("user_id",user_id);
            jsonObject.put("status",status_);

            //jsonObject.put("lat", latitude);
            //jsonObject.put("long", longitude);

            Log.e("Obj",jsonObject.toString());

        } catch (JSONException e) {
            e.printStackTrace();
        }

        // Request a string response from the provided URL.
        JsonObjectRequest jsonObjReq = new JsonObjectRequest(Request.Method.POST,
                Config.SHOW_ORDERS,jsonObject,
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

                                    JSONObject json1 = jsonarray.getJSONObject(i);

                                    JSONObject jsonObjOrder = json1.getJSONObject("Order");
                                    JSONObject jsonObjCurrency = jsonObjOrder.getJSONObject("Currency");
                                    OrderModelClass orderModelClass = new OrderModelClass();
                                    orderModelClass.setCurrency_symbol(jsonObjCurrency.optString("symbol"));
                                    orderModelClass.setOrder_price(jsonObjOrder.optString("price"));
                                    orderModelClass.setInstructions(jsonObjOrder.optString("instructions"));
                                    orderModelClass.setRestaurant_name(jsonObjOrder.optString("name"));
                                    orderModelClass.setOrder_quantity(jsonObjOrder.optString("quantity"));
                                    orderModelClass.setOrder_id(jsonObjOrder.optString("id"));
                                    orderModelClass.setOrder_created(jsonObjOrder.optString("created"));
                                    orderModelClass.setDelivery(jsonObjOrder.optString("delivery"));
                                    orderModelClass.setDeal_id(jsonObjOrder.optString("deal_id"));

                                    if(jsonObjOrder.getJSONArray("OrderMenuItem")!=null && jsonObjOrder.getJSONArray("OrderMenuItem").length()>0) {
                                        JSONArray jsonarrayOrder = jsonObjOrder.getJSONArray("OrderMenuItem");
                                        JSONObject jsonObjectMenu = jsonarrayOrder.getJSONObject(0);

                                        orderModelClass.setOrder_menu_id(jsonObjectMenu.optString("id"));
                                        orderModelClass.setOrder_name(jsonObjectMenu.optString("name"));

                                        JSONArray  jsonarrayExtraOrder = jsonObjectMenu.getJSONArray("OrderMenuExtraItem");

                                        if(jsonarrayExtraOrder!=null && jsonarrayExtraOrder.length()>0) {
                                            JSONObject jsonObjectExtraMenu = jsonarrayExtraOrder.getJSONObject(0);
                                            orderModelClass.setOrder_extra_item_name(jsonObjectExtraMenu.optString("name"));

                                        }

                                    }

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

                                    transparent_layer.setVisibility(View.GONE);
                                    progressDialog.setVisibility(View.GONE);
                                    TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
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
                                        editor.putString("delivery_",orderArrayList.get(position).getDelivery());
                                        editor.putString(PreferenceClass.ORDER_QUANTITY,orderArrayList.get(position).getOrder_quantity());
                                        editor.commit();
                                        Fragment restaurantMenuItemsFragment = new OrderDetailFragment();
                                        FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                                        transaction.add(R.id.order_fragment_container, restaurantMenuItemsFragment,"ParentFragment").commit();

                                    }
                                });


                            }else{
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
                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
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

        jsonObjReq.setRetryPolicy(new DefaultRetryPolicy(10000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        queue.add(jsonObjReq);

    }

    public void customDialogbox(){

        // custom dialog
        final Dialog dialog = new Dialog(getContext());
        dialog.setContentView(R.layout.custom_dialoge_box);
        dialog.setTitle("Order Filter");

        // set the custom dialog components - text, image and button

        RelativeLayout cancelDiv = (RelativeLayout) dialog.findViewById(R.id.forth);
        RelativeLayout currentOrderDiv = (RelativeLayout) dialog.findViewById(R.id.second);
        RelativeLayout pastOrderDiv = (RelativeLayout) dialog.findViewById(R.id.third);
        TextView first_tv = (TextView)dialog.findViewById(R.id.first_tv);
        TextView second_tv = (TextView)dialog.findViewById(R.id.second_tv);
        first_tv.setText("Current Orders");
        second_tv.setText("Past Orders");

        currentOrderDiv.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                STATUS_INACTIVE = false;
                getAllOrderParser(status_active);
                dialog.dismiss();
                FLAG_ACCEPTED_ORDER = true;
            }
        });

        pastOrderDiv.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                STATUS_INACTIVE = true;
               getAllOrderParser(status_inactive);
                dialog.dismiss();
                FLAG_ACCEPTED_ORDER = false;
            }
        });

        // if button is clicked, close the custom dialog
        cancelDiv.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.dismiss();
            }
        });

        dialog.show();
    }

}
