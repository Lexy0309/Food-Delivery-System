package com.dinosoftlabs.foodies.android.RActivitiesAndFragments;

import android.Manifest;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.ActivityCompat;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.afollestad.materialdialogs.DialogAction;
import com.afollestad.materialdialogs.MaterialDialog;
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
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.Query;
import com.google.firebase.database.ValueEventListener;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.PagerMainActivity;
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;
import com.github.javiersantos.materialstyleddialogs.MaterialStyledDialog;
import com.github.javiersantos.materialstyleddialogs.enums.Style;
import com.dinosoftlabs.foodies.android.Utils.TabLayoutUtils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

/**
 * Created by Nabeel on 1/16/2018.
 */

public class ROrderDetailFragment extends Fragment {

    ImageView back_icon,info_icon;
    Button confirm_btn;
    String order_number;
    RelativeLayout user_location_div,restaurant_address_div,user_call_div,hotel_call_div,pay_to_rest_div;
    SharedPreferences rDetail_Pref;
    TextView rider_jobs_title,r_hotel_name,order_number_tv,hotel_address_tv,r_total_bil_tv,card_detail_tv,time_tv,hotel_name2,rest_address_tv,
            rest_phone_number,user_name,user_address,user_phone_number,total_tax_tv,delivery_fee_tv,total_payment_tv,card_tv,tip_tv,
            pay_to_rest_tv,sub_total_payment_tv,totalText,inst_txt,inst_txt_user;

    String rest_lat,rest_long,user_lat,user_long,hotel_phone_number,user_phone_number_pref;

    CamomileSpinner customProgress;
    public RelativeLayout progressDialog;
    RelativeLayout transparent_layer;
    String serVerKey,user_id;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        getActivity().getWindow().setSoftInputMode(
                WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_HIDDEN);
        getActivity().getWindow().setSoftInputMode(
                WindowManager.LayoutParams.SOFT_INPUT_STATE_VISIBLE | WindowManager.LayoutParams.SOFT_INPUT_ADJUST_RESIZE);
        View v = inflater.inflate(R.layout.rider_order_detail, container, false);
        rDetail_Pref = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);
        user_id = rDetail_Pref.getString(PreferenceClass.pre_user_id,"");
        FrameLayout frameLayout =v.findViewById(R.id.main_container_order_detail);
        frameLayout.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View v, MotionEvent event) {
                return true;
            }
        });
        FontHelper.applyFont(getContext(),frameLayout, AllConstants.verdana);
        init(v);
        //Rider Func
        showRiderTracking();


        // End
        return v;
    }

    public void init(View v){
        customProgress = v.findViewById(R.id.customProgress);
        progressDialog = v.findViewById(R.id.progressDialog);
        customProgress.start();
        transparent_layer = v.findViewById(R.id.transparent_layer);
        info_icon = v.findViewById(R.id.info_icon);

        String hotel_name = rDetail_Pref.getString(PreferenceClass.RIDER_HOTEL_NAME,"");
        order_number = rDetail_Pref.getString(PreferenceClass.RIDER_ORDER_NUMBER,"");
      //  String hotel_zip = rDetail_Pref.getString(PreferenceClass.RIDER_HOTEL_ZIP,"");
      //  String hotel_city = rDetail_Pref.getString(PreferenceClass.RIDER_HOTEL_CITY,"");
      //  String hotel_state = rDetail_Pref.getString(PreferenceClass.RIDER_HOTEL_STATE,"");
      //  String hotel_country = rDetail_Pref.getString(PreferenceClass.RIDER_HOTEL_COUNTRY,"");
       // String cod = rDetail_Pref.getString(PreferenceClass.RIDER_PAYMENT_STATUS,"");
        String sub_total = rDetail_Pref.getString(PreferenceClass.RIDER_TOTAL_PAYMENT,"");
        if(sub_total.isEmpty()){
            sub_total = "0";
        }
        String symbol = rDetail_Pref.getString(PreferenceClass.RIDER_ORDER_SYMBOL,"");
      /*  String order_time = rDetail_Pref.getString(PreferenceClass.RIDER_TIME,"");
        final String hotel_phone_number = rDetail_Pref.getString(PreferenceClass.RIDER_HOTEL_PHONE,"");
        String user_f_name = rDetail_Pref.getString(PreferenceClass.RIDER_USER_F_NAME,"");
        String user_l_name = rDetail_Pref.getString(PreferenceClass.RIDER_USER_F_NAME,"");
        String user_street = rDetail_Pref.getString(PreferenceClass.RIDER_USER_STREET,"");
        String apartment = rDetail_Pref.getString(PreferenceClass.RIDER_USER_APARTMENT,"");
        String user_city = rDetail_Pref.getString(PreferenceClass.RIDER_USER_CITY,"");
        String user_state = rDetail_Pref.getString(PreferenceClass.RIDER_USER_STATE,"");
        final String user_phone_number_pref = rDetail_Pref.getString(PreferenceClass.RIDER_USER_PHONE,"");
        String tax = rDetail_Pref.getString(PreferenceClass.RIDER_ORDER_TAX,"");
        String delivery_fee = rDetail_Pref.getString(PreferenceClass.RIDER_ORDER_DELIVER_FEE,"");
        if(delivery_fee.isEmpty()){
            delivery_fee = "0";
        }
        final String rest_lat = rDetail_Pref.getString(PreferenceClass.RIDER_HOTEL_LAT,"");
        final String rest_long = rDetail_Pref.getString(PreferenceClass.RIDER_HOTEL_LONG,"");
        final String user_lat = rDetail_Pref.getString(PreferenceClass.RIDER_USER_LAT,"");
        final String user_long = rDetail_Pref.getString(PreferenceClass.RIDER_USER_LONG,"");*/
        inst_txt_user = v.findViewById(R.id.inst_txt_user);
        inst_txt = v.findViewById(R.id.inst_txt);
        rider_jobs_title = v.findViewById(R.id.rider_jobs_title);
        r_hotel_name = v.findViewById(R.id.r_hotel_name);
        order_number_tv = v.findViewById(R.id.order_number_tv);
        hotel_address_tv = v.findViewById(R.id.hotel_address_tv);
        r_total_bil_tv = v.findViewById(R.id.r_total_bil_tv);
        card_detail_tv = v.findViewById(R.id.card_detail_tv);
        time_tv = v.findViewById(R.id.time_tv);
        hotel_name2 = v.findViewById(R.id.hotel_name2);
        rest_address_tv = v.findViewById(R.id.rest_address_tv);
        rest_phone_number = v.findViewById(R.id.rest_phone_number);
        user_name = v.findViewById(R.id.user_name);
        user_address = v.findViewById(R.id.user_address);
        user_phone_number = v.findViewById(R.id.user_phone_number);
        total_tax_tv = v.findViewById(R.id.total_tax_tv);
        delivery_fee_tv = v.findViewById(R.id.delivery_fee_tv);
        total_payment_tv = v.findViewById(R.id.total_payment_tv);
        card_tv = v.findViewById(R.id.card_tv);
        confirm_btn = v.findViewById(R.id.confirm_btn);
        tip_tv = v.findViewById(R.id.tip_tv);

        pay_to_rest_tv = v.findViewById(R.id.pat_to_rest_tv);
        pay_to_rest_div = v.findViewById(R.id.pay_to_rest_div);

        sub_total_payment_tv = v.findViewById(R.id.sub_total_payment_tv);
        totalText = v.findViewById(R.id.totalText);

        rider_jobs_title.setText("Order #"+order_number);
        order_number_tv.setText("Order #"+order_number);
        r_hotel_name.setText(hotel_name);
        r_total_bil_tv.setText(symbol+sub_total);


        getserverkeyCurrent(order_number);

       /* hotel_address_tv.setText(hotel_zip+", "+hotel_city+", "+hotel_state+", "+hotel_country);
        r_total_bil_tv.setText(symbol+sub_total);
        card_detail_tv.setText(cod);
        time_tv.setText(order_time);*/
        hotel_name2.setText(hotel_name);
      /*  rest_address_tv.setText(hotel_zip+", "+hotel_city+", "+hotel_state+", "+hotel_country);
        rest_phone_number.setText(hotel_phone_number);
        user_name.setText(user_f_name+" "+user_l_name);
        user_address.setText(user_street+", " +user_city+", "+user_state);
        user_phone_number.setText(user_phone_number_pref);
        total_tax_tv.setText(symbol+tax);
        delivery_fee_tv.setText(symbol+delivery_fee);

        Double total = Double.parseDouble(sub_total)+Double.parseDouble(delivery_fee);

        total_payment_tv.setText(symbol+String.valueOf(total));
        card_tv.setText(cod);*/

        user_location_div = v.findViewById(R.id.user_location_div);
        restaurant_address_div = v.findViewById(R.id.restaurant_address_div);

        restaurant_address_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                new MaterialStyledDialog.Builder(getContext())
                        .setTitle("GOOGLE MAP!")
                        .setDescription("This will open google map to let you track path.")
                        .setPositiveText("GOOGLE MAP")
                        .setStyle(Style.HEADER_WITH_TITLE)
                        .onPositive(new MaterialDialog.SingleButtonCallback() {
                            @Override
                            public void onClick(@NonNull MaterialDialog dialog, @NonNull DialogAction which) {

                                String uri = "http://maps.google.com/maps?daddr=" + rest_lat + "," + rest_long + " (" + "Where the Restaurant is" + ")";
                                Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(uri));
                                intent.setPackage("com.google.android.apps.maps");
                                startActivity(intent);

                            }
                        })
                        .setNegativeText("LATER")
                        .onNegative(new MaterialDialog.SingleButtonCallback() {
                            @Override
                            public void onClick(@NonNull MaterialDialog dialog, @NonNull DialogAction which) {
                                dialog.cancel();

                            }
                        })
                        .show();

            }
        });

        user_location_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                new MaterialStyledDialog.Builder(getContext())
                        .setTitle("GOOGLE MAP!")
                        .setDescription("This will open google map to let you track path.")
                        .setStyle(Style.HEADER_WITH_TITLE)
                        .setPositiveText("GOOGLE MAP")

                        .onPositive(new MaterialDialog.SingleButtonCallback() {
                            @Override
                            public void onClick(@NonNull MaterialDialog dialog, @NonNull DialogAction which) {

                                String uri = "http://maps.google.com/maps?daddr=" + user_lat + "," + user_long + " (" + "Where the User Location is" + ")";
                                Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(uri));
                                intent.setPackage("com.google.android.apps.maps");
                                startActivity(intent);

                            }
                        })
                        .setNegativeText("LATER")
                        .onNegative(new MaterialDialog.SingleButtonCallback() {
                            @Override
                            public void onClick(@NonNull MaterialDialog dialog, @NonNull DialogAction which) {
                                dialog.cancel();

                            }
                        })
                        .show();
            }
        });

        hotel_call_div = v.findViewById(R.id.hotel_call_div);
        user_call_div = v.findViewById(R.id.user_call_div);

        hotel_call_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                new MaterialStyledDialog.Builder(getContext())
                        .setTitle("MAKE A CALL!")
                        .setDescription("This will call on hotel number.")
                        .setStyle(Style.HEADER_WITH_TITLE)
                        .setPositiveText("CALL")

                        .onPositive(new MaterialDialog.SingleButtonCallback() {
                            @Override
                            public void onClick(@NonNull MaterialDialog dialog, @NonNull DialogAction which) {


                                Intent phoneIntent = new Intent(Intent.ACTION_CALL);
                                phoneIntent.setData(Uri.parse("tel:"+hotel_phone_number));
                                if (ActivityCompat.checkSelfPermission(getContext(), Manifest.permission.CALL_PHONE) != PackageManager.PERMISSION_GRANTED) {
                                    return;
                                }
                                startActivity(phoneIntent);

                            }
                        })
                        .setNegativeText("LATER")
                        .onNegative(new MaterialDialog.SingleButtonCallback() {
                            @Override
                            public void onClick(@NonNull MaterialDialog dialog, @NonNull DialogAction which) {
                                dialog.cancel();

                            }
                        })
                        .show();
            }
        });

        user_call_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {


                new MaterialStyledDialog.Builder(getContext())
                        .setTitle("MAKE A CALL!")
                        .setDescription("This will call on user number.")
                        .setStyle(Style.HEADER_WITH_TITLE)
                        .setPositiveText("CALL")

                        .onPositive(new MaterialDialog.SingleButtonCallback() {
                            @Override
                            public void onClick(@NonNull MaterialDialog dialog, @NonNull DialogAction which) {


                                Intent phoneIntent = new Intent(Intent.ACTION_CALL);
                                phoneIntent.setData(Uri.parse("tel:"+user_phone_number_pref));
                                if (ActivityCompat.checkSelfPermission(getContext(), Manifest.permission.CALL_PHONE) != PackageManager.PERMISSION_GRANTED) {
                                    return;
                                }
                                startActivity(phoneIntent);

                            }
                        })
                        .setNegativeText("LATER")
                        .onNegative(new MaterialDialog.SingleButtonCallback() {
                            @Override
                            public void onClick(@NonNull MaterialDialog dialog, @NonNull DialogAction which) {
                                dialog.cancel();

                            }
                        })
                        .show();

            }
        });

        back_icon = v.findViewById(R.id.back_icon);
        back_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if(RTodayJobFragment.FLAG_TODAY_JOB){

                    Fragment restaurantMenuItemsFragment = new RTodayJobFragment();
                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                    transaction.add(R.id.main_container_order_detail, restaurantMenuItemsFragment,"parent").commit();
                    RTodayJobFragment.FLAG_TODAY_JOB = false;


                }else if(RJobsFragment.FLAG_CURRENT_JOB) {
                    RJobsFragment rJobsFragment = new RJobsFragment();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();
                    transaction.replace(R.id.main_container_order_detail, rJobsFragment);
                    transaction.addToBackStack(null);
                    transaction.commit();
                    RJobsFragment.FLAG_CURRENT_JOB = false;
                }
            }
        });

        confirm_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                trackRiderStatus();
            }
        });


        info_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Fragment restaurantMenuItemsFragment = new ROrderDetailWithItems();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.main_container_order_detail, restaurantMenuItemsFragment,"parent").commit();
            }
        });

        getOrderDetailItems();

    }

    public void showRiderTracking(){

        RequestQueue queue = Volley.newRequestQueue(getContext());

        JSONObject jsonObject = new JSONObject();

        try {
            jsonObject.put("order_id",order_number);
        } catch (JSONException e) {

        }

        JsonObjectRequest trackingRequest = new JsonObjectRequest(Request.Method.POST, Config.SHOW_RIDER_TRACKING, jsonObject, new Response.Listener<JSONObject>() {
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

                        Log.e("MSG",jsonResponse.toString());

                        JSONObject json = new JSONObject(jsonResponse.toString());
                        String jsonarray = json.optString("msg");

                        confirm_btn.setText(jsonarray);

                    }


                }catch (Exception e){

                    e.getMessage();
                }


            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
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

        trackingRequest.setRetryPolicy(new DefaultRetryPolicy(5000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));

        queue.add(trackingRequest);
    }

    public String getCurrentTimeStamp() {
        SimpleDateFormat sdfDate = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");//dd/MM/yyyy
        Date now = new Date();
        String strDate = sdfDate.format(now);
        return strDate;
    }


    public void getserverkeyCurrent(String order_id){
        DatabaseReference mref= FirebaseDatabase.getInstance().getReference();

        final Query query2 =mref.child("RiderOrdersList").child(user_id).child("PendingOrders").orderByChild("order_id").equalTo(order_id);
        query2.addListenerForSingleValueEvent(new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot dataSnapshot) {
                for (DataSnapshot nodeDataSnapshot : dataSnapshot.getChildren()) {
                    serVerKey = nodeDataSnapshot.getKey(); // this key is `K1NRz9l5PU_0CFDtgXz`
                }

            }

            @Override
            public void onCancelled(DatabaseError databaseError) {

            }
        });


    }

    public void trackRiderStatus(){
        progressDialog.setVisibility(View.VISIBLE);
        transparent_layer.setVisibility(View.VISIBLE);
        RequestQueue queue = Volley.newRequestQueue(getContext());

        String currentTime = getCurrentTimeStamp();

        JSONObject jsonObject = new JSONObject();

        try {
            jsonObject.put("order_id",order_number);
            jsonObject.put("time",currentTime);
        } catch (JSONException e) {

        }

        JsonObjectRequest trackingRequest = new JsonObjectRequest(Request.Method.POST, Config.TRACK_RIDER_STATUS, jsonObject, new Response.Listener<JSONObject>() {
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

                        progressDialog.setVisibility(View.GONE);
                        transparent_layer.setVisibility(View.GONE);

                        Log.e("MSG",jsonResponse.toString());

                        JSONObject json = new JSONObject(jsonResponse.toString());
                        String jsonarray = json.optString("msg");

                        confirm_btn.setText(jsonarray);

                        if(confirm_btn.getText().toString().equalsIgnoreCase("order completed")||
                                confirm_btn.getText().toString().equalsIgnoreCase("order already completed")  ) {

                            final DatabaseReference add_to_onother= FirebaseDatabase.getInstance().getReference()
                                    .child("RiderOrdersList").child(user_id);
                            add_to_onother.child("PendingOrders").child(serVerKey).setValue(null);
                            RJobsFragment rJobsFragment = new RJobsFragment();
                            FragmentTransaction transaction = getFragmentManager().beginTransaction();
                            transaction.replace(R.id.main_container_order_detail, rJobsFragment);
                            transaction.addToBackStack(null);
                            transaction.commit();
                        }


                    }


                }catch (Exception e){

                    e.getMessage();
                }


            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
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

        trackingRequest.setRetryPolicy(new DefaultRetryPolicy(5000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));

        queue.add(trackingRequest);
    }



    public void getOrderDetailItems(){
        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);


        //   listDataChild = new HashMap<MenuItemModel, ArrayList<String>>();
        RequestQueue queue = Volley.newRequestQueue(getContext());
        JSONObject orderJsonObject = new JSONObject();
        try {
            orderJsonObject.put("order_id",order_number);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest orderJsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.SHOW_ORDER_DETAIL, orderJsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                String strJson =  response.toString();
                JSONObject jsonResponse = null;
                try {
                    jsonResponse = new JSONObject(strJson);

                    Log.d("JSONPost", jsonResponse.toString());

                    int code_id  = Integer.parseInt(jsonResponse.optString("code"));

                    if(code_id == 200) {

                        JSONObject json = new JSONObject(jsonResponse.toString());
                        JSONArray jsonArray = json.getJSONArray("msg");

                        for (int i=0;i<jsonArray.length();i++) {

                            JSONObject allJsonObject = jsonArray.getJSONObject(i);
                            JSONObject orderJsonObject = allJsonObject.getJSONObject("Order");
                            JSONObject userInfoObj = allJsonObject.getJSONObject("UserInfo");
                            JSONObject userAddressObj = allJsonObject.getJSONObject("Address");
                            JSONObject restaurantJsonObject = allJsonObject.getJSONObject("Restaurant");
                            JSONObject taxObj = restaurantJsonObject.getJSONObject("Tax");
                            JSONObject restaurantCurrencuObj = restaurantJsonObject.getJSONObject("Currency");
                            String currency_symbol = restaurantCurrencuObj.optString("symbol");
                            String time = orderJsonObject.optString("created");


                            // Get date from string
                            SimpleDateFormat dateFormatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
                            Date date = dateFormatter.parse(time);

                            // Get time from date
                            SimpleDateFormat timeFormatter = new SimpleDateFormat("h:mm a");
                            String displayValue = timeFormatter.format(date);

                            time_tv.setText(displayValue);

                            user_lat = userAddressObj.optString("lat");
                            user_long = userAddressObj.optString("long");
                            String tip = orderJsonObject.optString("rider_tip");
                            tip_tv.setText(currency_symbol+" "+tip);
                            String delivery_fee = orderJsonObject.optString("delivery_fee");
                            delivery_fee_tv.setText(currency_symbol+" "+delivery_fee);
                            String first_name = userInfoObj.optString("first_name");
                            String last_name = userInfoObj.optString("last_name");
                            user_name.setText(first_name + " " + last_name);
                            user_phone_number.setText(userInfoObj.optString("phone"));
                          /*  order_user_name_tv.setText(first_name + " " + last_name);
                            order_user_number_tv.setText(userInfoObj.optString("phone"));*/
                            String street_user = userAddressObj.optString("street");
                            String zip_user = userAddressObj.optString("zip");
                            String city_user = userAddressObj.optString("city");
                            String state_user = userAddressObj.optString("state");
                            String country_user = userAddressObj.optString("country");

                            user_address.setText(street_user + ", " + city_user);
                            inst_txt_user.setText(userAddressObj.optString("instructions"));
                            inst_txt.setText(orderJsonObject.optString("accepted_reason"));

                          /*  if (delivery.equalsIgnoreCase("0")) {
                                order_user_address_tv.setText("Pick Up");
                            } else {
                                order_user_address_tv.setText(street_user + ", " + city_user);
                            }*/

                        /*    if (order_user_address_tv.getText().toString().equalsIgnoreCase("Pick Up")) {
                                track_order_div.setBackgroundColor(getContext().getResources().getColor(R.color.trackColor));
                                pick_up = 1;
                            }*/



                            //    order_user_address_tv.setText(street_user + ", " + city_user);


                           // inst_tv.setText(orderJsonObject.optString("instructions"));
                          //  total_amount_tv.setText(currency_symbol + orderJsonObject.optString("price"));
                            String subTotal= orderJsonObject.optString("sub_total");
                            String price = orderJsonObject.optString("price");
                            sub_total_payment_tv.setText(currency_symbol+" "+subTotal);
                            String getPaymentMethodTV = orderJsonObject.optString("cod");
                            if (getPaymentMethodTV.equalsIgnoreCase("0")) {
                                card_tv.setText("Credit Card");
                                card_detail_tv.setText("Credit Card");
                                pay_to_rest_div.setVisibility(View.GONE);
                                total_payment_tv.setText(currency_symbol+" "+price);
                            } else {
                                card_tv.setText("Cash On Delivery");
                                card_detail_tv.setText("Cash On Delivery");
                                String payToRest = String.valueOf(Double.parseDouble(delivery_fee)+Double.parseDouble(tip));
                                pay_to_rest_tv.setText(currency_symbol+" "+String.valueOf (Double.parseDouble(price)-Double.parseDouble(payToRest)));
                                totalText.setText("Collect From Customer");
                                total_payment_tv.setText(currency_symbol+" "+price);

                            }

                           /* hotel_name_tv.setText(restaurantJsonObject.optString("name"));
                            hotel_phone_number_tv.setText(restaurantJsonObject.optString("phone"));*/
                            JSONObject restaurantAddress = restaurantJsonObject.getJSONObject("RestaurantLocation");

                            rest_lat =restaurantAddress.optString("lat");
                            rest_long = restaurantAddress.optString("long");
                            String street = restaurantAddress.optString("street");
                            String zip = restaurantAddress.optString("zip");
                            String city = restaurantAddress.optString("city");
                            String state = restaurantAddress.optString("state");
                            String country = restaurantAddress.optString("country");

                            rest_address_tv.setText(street + ", " + city);

                            hotel_address_tv.setText(street + ", " + city);
                            rest_phone_number.setText(restaurantJsonObject.optString("phone"));

                         /*   hotel_add_tv.setText(street + ", " + city);
                            if (HJobsFragment.FLAG_HJOBS) {
                                hotel_add_tv.setText(street + ", " + city);
                            }
*/
                            //// Total Payment
                            String tax = orderJsonObject.optString("tax");





                         //   total_delivery_fee_tv.setText(currency_symbol + delivery_fee);
                            String tax_free = restaurantJsonObject.optString("tax_free");
                            if (tax_free.equalsIgnoreCase("1")) {
                                total_tax_tv.setText("(" + "0" + "%)");

                            }
                            else {
                                total_tax_tv.setText(currency_symbol+" "+tax);
                            }
                            // Double getTotalTax = Double.parseDouble(tax)*Double.parseDouble(sub_total)/100;
                          //  total_tex_tv.setText(tax);


                           // sub_total_amount_tv.setText(subTotal);



                            TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                            transparent_layer.setVisibility(View.GONE);
                            progressDialog.setVisibility(View.GONE);

                            //// End

                        }
                    }


                }catch (Exception e){
                    e.getMessage();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
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

        queue.add(orderJsonObjectRequest);

    }


}
