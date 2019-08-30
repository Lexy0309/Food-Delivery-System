package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.app.Dialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.text.InputType;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.widget.Button;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

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
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;
import com.dinosoftlabs.foodies.android.Utils.TabLayoutUtils;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.DecimalFormat;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.HashMap;
import java.util.Map;

/**
 * Created by Nabeel on 1/6/2018.
 */

public class DealOrderFragment extends Fragment {

    ProgressBar pb_deal_order;
    SharedPreferences deal_order_pref;
    Button cancel_deal_btn;
    ImageView back_icon;
    String deal_name, deals_tax, deal_price, deal_currency, delivery_fee, delivery_address_street, delivery_address_state, delivery_address_city,
            apartment, card_number, card_brand, deal_desc, user_id, payment_id, address_id, rest_name,riderTip,res_id,formattedDate,deal_id,is_tax_free;
    TextView rest_name_tv, deal_desc_tv, deal_price_tv, sub_total_price_tv, tax_tv, total_delivery_fee_tv, total_deal_order_tv, delivery_address_tv,
            credit_card_number_tv,total_tex_tv,deal_name_tv,total_sum_tv,rider_tip,rider_tip_price_tv,decline_tv,accept_tv;
    RelativeLayout deal_payment_method_div, deal_address_div, cart_check_out_div,tip_div,accept_div,decline_div,cart_address_div;
    public static boolean FLAG_DEAL_ORDER, DEALS,FLAG_DEAL_ADDRESS;
    int deal_quantity;
    double getTax, getFinalPrice,grandTotal;
    boolean getLoINSession,PICK_UP;

    Double previousRiderTip = 0.0;
    public static boolean DEAL_ADDRESS,DEAL_LOGIN,DEAL_PAYMENT_METHOD,DEAL_PLACED;

    CamomileSpinner pbHeaderProgress;

    RelativeLayout transparent_layer,progressDialog;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.deals_report_layout, container, false);
        deal_order_pref = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);
        getLoINSession = deal_order_pref.getBoolean(PreferenceClass.IS_LOGIN,false);
        grandTotal = 0.0;
        riderTip = "0";
        FrameLayout frameLayout = view.findViewById(R.id.deal_order_main_container);
        FontHelper.applyFont(getContext(), frameLayout, AllConstants.verdana);
        frameLayout.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                return true;
            }
        });
        DEALS = true;

        initUI(view);
        return view;

    }
    @SuppressWarnings("deprecation")
    public void initUI(View v){
        decline_div = v.findViewById(R.id.decline_div);
        accept_div = v.findViewById(R.id.accept_div);
        decline_tv = v.findViewById(R.id.decline_tv);
        accept_tv = v.findViewById(R.id.accept_tv);
        rider_tip_price_tv = v.findViewById(R.id.rider_tip_price_tv);
        rider_tip = v.findViewById(R.id.rider_tip);
        deal_name = deal_order_pref.getString(PreferenceClass.DELAS_NAME,"");

        deal_price = deal_order_pref.getString(PreferenceClass.DEALS_PRICE,"");
        deal_currency = deal_order_pref.getString(PreferenceClass.DEALS_CURRENCY_SYMBOL,"");

        delivery_address_street = deal_order_pref.getString(PreferenceClass.STREET,"");
        delivery_address_state = deal_order_pref.getString(PreferenceClass.STATE,"");
        delivery_address_city = deal_order_pref.getString(PreferenceClass.CITY,"");
        apartment = deal_order_pref.getString(PreferenceClass.APARTMENT,"");
        user_id = deal_order_pref.getString(PreferenceClass.pre_user_id,"");
        payment_id = deal_order_pref.getString(PreferenceClass.PAYMENT_ID,"");
        address_id = deal_order_pref.getString(PreferenceClass.ADDRESS_ID,"");
        deal_id = deal_order_pref.getString(PreferenceClass.DEAL_ID,"");
        // rest_id = deal_order_pref.getString(Pre)
        card_number = deal_order_pref.getString(PreferenceClass.CREDIT_CARD_ARRAY,"");
        card_brand = deal_order_pref.getString(PreferenceClass.CREDIT_CARD_BRAND,"");
        deal_desc = deal_order_pref.getString(PreferenceClass.DEALS_DESC,"");
        res_id = deal_order_pref.getString(PreferenceClass.RESTAURANT_ID,"");

        is_tax_free = deal_order_pref.getString(PreferenceClass.IS_DELIVERY_FREE,"");

        if(is_tax_free.equalsIgnoreCase("1")) {
            deals_tax = "0";
        }
        else {
            deals_tax = deal_order_pref.getString(PreferenceClass.DEALS_TAX, "");
        }

        rest_name = deal_order_pref.getString(PreferenceClass.RESTAURANT_NAME,"");

        deal_quantity = deal_order_pref.getInt(PreferenceClass.DEALS_QUANTITY,1);

        getFinalPrice = Double.parseDouble(deal_price)*Double.parseDouble(String.valueOf(deal_quantity));


        ///All View
        progressDialog = v.findViewById(R.id.progressDialog);
        transparent_layer = v.findViewById(R.id.transparent_layer);
        pbHeaderProgress = v.findViewById(R.id.dealOrderProgress);
        pbHeaderProgress.start();
        credit_card_number_tv = v.findViewById(R.id.credit_card_number_tv);
        deal_payment_method_div = v.findViewById(R.id.deal_payment_method_div);
        deal_address_div = v.findViewById(R.id.deal_address_div);
        delivery_address_tv = v.findViewById(R.id.delivery_address_tv);
        rest_name_tv = v.findViewById(R.id.rest_name_tv);
        rest_name_tv.setText(rest_name);
        deal_desc_tv = v.findViewById(R.id.deal_desc_tv);
        deal_desc_tv.setText(deal_desc);
        sub_total_price_tv = v.findViewById(R.id.sub_total_price_tv);
        sub_total_price_tv.setText(deal_currency+""+getFinalPrice);

        credit_card_number_tv.setText("Select Payment Method");

        card_number = deal_order_pref.getString(PreferenceClass.CREDIT_CARD_ARRAY, "");
        if (AddPaymentFragment.FLAG_PAYMENT_METHOD) {
            credit_card_number_tv.setText("Cash on delivery");
            credit_card_number_tv.setTextColor(getResources().getColor(R.color.black));
           // AddPaymentFragment.FLAG_PAYMENT_METHOD = false;
        } else {
            if(card_number.isEmpty()){
                credit_card_number_tv.setText("Select Payment Method");
            }
            else
            {
                credit_card_number_tv.setText("**** **** **** " + card_number);
            }
            credit_card_number_tv.setTextColor(getResources().getColor(R.color.black));

        }



        deal_price_tv = v.findViewById(R.id.deal_price_tv);
        deal_price_tv.setText(deal_currency+deal_price);

        tax_tv = v.findViewById(R.id.tax_tv);
        tax_tv.setText("("+deals_tax+"%)");
        total_tex_tv = v.findViewById(R.id.total_tex_tv);
        getTax = getFinalPrice*Double.parseDouble(deals_tax)/100;
        total_tex_tv.setText(deal_currency+getTax);

        deal_name_tv = v.findViewById(R.id.deal_name_tv);
        deal_name_tv.setText(deal_name + " (x"+deal_quantity+")");

        total_delivery_fee_tv = v.findViewById(R.id.total_delivery_fee_tv);

        if(FLAG_DEAL_ADDRESS) {
            delivery_address_tv.setText(delivery_address_street +  ", " + delivery_address_city);
            delivery_fee = deal_order_pref.getString(PreferenceClass.ADDRESS_DELIVERY_FEE,"");
            total_delivery_fee_tv.setText(deal_currency+delivery_fee);
            FLAG_DEAL_ADDRESS = false;
        }
        else {
            delivery_address_tv.setText("Select Delivery Address");
            total_delivery_fee_tv.setText("0");
            delivery_fee = "0";
        }

        total_sum_tv = v.findViewById(R.id.total_sum_tv);
        grandTotal = Double.parseDouble(delivery_fee)+getFinalPrice+getTax;
        total_sum_tv.setText(deal_currency+new DecimalFormat("##.##").format(grandTotal));

        tip_div = v.findViewById(R.id.tip_div);

        tip_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                addRiderTip();
            }
        });

        back_icon = v.findViewById(R.id.back_icon);
        back_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if(DealsFragment.OPEN_DEALS){
                    Fragment restaurantMenuItemsFragment = new DealsDetailFragment();
                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                    transaction.add(R.id.deal_order_main_container, restaurantMenuItemsFragment, "parent").commit();
                    DealsFragment.OPEN_DEALS = false;
                }
                else {
                    Fragment restaurantMenuItemsFragment = new DealsDetailRestFragment();
                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                    transaction.add(R.id.deal_order_main_container, restaurantMenuItemsFragment, "parent").commit();
                    DealsFragment.FLAG_DEAL_FRAGMENT = true;
                }
            }
        });



        deal_address_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if(!getLoINSession){
                    Fragment restaurantMenuItemsFragment = new UserAccountFragment();
                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                    transaction.add(R.id.deal_order_main_container, restaurantMenuItemsFragment,"parent").commit();
                    DEAL_ADDRESS = true;
                    DEAL_LOGIN = true;
                }
                else {
                    DEAL_ADDRESS = true;
                    Fragment restaurantMenuItemsFragment = new AddressListFragment();
                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                    transaction.add(R.id.deal_order_main_container, restaurantMenuItemsFragment, "parent").commit();
                    SharedPreferences.Editor editor = deal_order_pref.edit();
                    editor.putString("grandTotal",String.valueOf(grandTotal));
                    editor.putString(PreferenceClass.RESTAURANT_ID,res_id).apply();
                }

            }
        });

        deal_payment_method_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if(!getLoINSession){
                    Fragment restaurantMenuItemsFragment = new UserAccountFragment();
                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                    transaction.add(R.id.deal_order_main_container, restaurantMenuItemsFragment,"parent").commit();
                    DEAL_PAYMENT_METHOD = true;
                    DEAL_LOGIN = true;
                }
                else {
                    DEAL_PAYMENT_METHOD = true;
                    Fragment restaurantMenuItemsFragment = new AddPaymentFragment();
                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                    transaction.add(R.id.deal_order_main_container, restaurantMenuItemsFragment, "parent").commit();
                }
            }
        });
        cart_check_out_div = v.findViewById(R.id.cart_check_out_div);

        cart_check_out_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if(delivery_address_tv.getText().toString().equalsIgnoreCase("Select Delivery Address")
                        || credit_card_number_tv.getText().toString().equalsIgnoreCase("Select Payment Method")
                        )
                {
                    Toast.makeText(getContext(),"Delivery Address OR Payment Method is Missed",Toast.LENGTH_LONG).show();
                }else {
                    dealOrder();
                }
            }
        });


        pickUpOrDelivery();
    }

    public void addRiderTip(){
        // custom dialog
        final Dialog dialog = new Dialog(getContext());
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        dialog.setContentView(R.layout.custom_dialog_cart);

        final EditText ed_text = dialog.findViewById(R.id.ed_text);
        ed_text.setInputType(InputType.TYPE_CLASS_NUMBER);
        TextView title = dialog.findViewById(R.id.title);
        title.setText("Add Rider Tip");
        ed_text.setHint("Enter Tip Here");
        // set the custom dialog components - text, image and button

        Button cancelDiv = (Button) dialog.findViewById(R.id.cancel_btn);
        Button done_btn =  (Button) dialog.findViewById(R.id.done_btn);

        done_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                riderTip = ed_text.getText().toString();
                PICK_UP = false;
                getTotalSumTip(riderTip,PICK_UP);
                rider_tip_price_tv.setText(deal_currency+riderTip);
                rider_tip.setText(deal_currency+riderTip);
                dialog.dismiss();
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

    public void getTotalSumTip(String riderTip,boolean rider_tip_pick_up){
        if(rider_tip_pick_up){
            grandTotal = Double.parseDouble(String.valueOf(grandTotal-Double.parseDouble(riderTip)));
        }
        else {

            grandTotal = Double.parseDouble(String.valueOf(grandTotal + Double.parseDouble(riderTip)));
            grandTotal = grandTotal-previousRiderTip;
            previousRiderTip = Double.parseDouble(riderTip);

        }
        total_sum_tv.setText(deal_currency+new DecimalFormat("##.##").format(grandTotal));

    }

    @SuppressWarnings("deprecation")
    public void pickUpOrDelivery(){
        decline_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                decline_div.setBackground(getResources().getDrawable(R.drawable.round_shape_btn_login));
                accept_div.setBackground(getResources().getDrawable(R.drawable.round_shape_btn_grey));
                decline_tv.setTextColor(getResources().getColor(R.color.colorWhite));
                accept_tv.setTextColor(getResources().getColor(R.color.or_color_name));
                rider_tip_price_tv.setText(deal_currency+"0");
                total_delivery_fee_tv.setText(deal_currency+"0");
                rider_tip.setText(deal_currency+"0");
                delivery_address_tv.setText("Pick Up");
                PICK_UP = true;
                getTotalSumDeliveryFee(delivery_fee,PICK_UP);
                getTotalSumTip(riderTip,PICK_UP);
                deal_address_div.setOnTouchListener(new View.OnTouchListener() {
                    @Override
                    public boolean onTouch(View view, MotionEvent motionEvent) {
                        return true;
                    }
                });

                tip_div.setOnTouchListener(new View.OnTouchListener() {
                    @Override
                    public boolean onTouch(View view, MotionEvent motionEvent) {
                        return true;
                    }
                });

            }
        });

        accept_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                decline_div.setBackground(getResources().getDrawable(R.drawable.round_shape_btn_grey));
                accept_div.setBackground(getResources().getDrawable(R.drawable.round_shape_btn_login));
                decline_tv.setTextColor(getResources().getColor(R.color.or_color_name));
                accept_tv.setTextColor(getResources().getColor(R.color.colorWhite));
                rider_tip_price_tv.setText(deal_currency+riderTip);
                total_delivery_fee_tv.setText(deal_currency+delivery_fee);
                rider_tip.setText(deal_currency+riderTip);
                if(delivery_address_street.isEmpty()&&apartment.isEmpty()&&delivery_address_city.isEmpty()&&delivery_address_state.isEmpty()){
                    delivery_address_tv.setText("Select Delivery Address");
                }
                else {
                    delivery_address_tv.setText(delivery_address_street + " " + apartment + " " + delivery_address_city + " " + delivery_address_state);
                }
                PICK_UP = false;

                previousRiderTip = 0.0;
                getTotalSumDeliveryFee(delivery_fee,PICK_UP);
                getTotalSumTip(riderTip,PICK_UP);

                deal_address_div.setOnTouchListener(new View.OnTouchListener() {
                    @Override
                    public boolean onTouch(View view, MotionEvent motionEvent) {
                        return false;
                    }
                });

                tip_div.setOnTouchListener(new View.OnTouchListener() {
                    @Override
                    public boolean onTouch(View view, MotionEvent motionEvent) {
                        return false;
                    }
                });
            }
        });
    }


    public void getTotalSumDeliveryFee(String deliveryFee,boolean picu_up){

        if(picu_up){
            grandTotal = Double.parseDouble(String.valueOf(grandTotal-Double.parseDouble(deliveryFee)));
        }
        else {
            grandTotal = Double.parseDouble(String.valueOf(grandTotal + Double.parseDouble(deliveryFee)));
        }
        total_sum_tv.setText(deal_currency+new DecimalFormat("##.##").format(grandTotal));

    }


    public void dealOrder() {

        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);

        Calendar c = Calendar.getInstance();
        System.out.println("Current time =&gt; "+c.getTime());

        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        formattedDate = df.format(c.getTime());
     //   pb_deal_order.setVisibility(View.VISIBLE);
        RequestQueue postOrderRequestQueue = Volley.newRequestQueue(getContext());
        JSONObject jsonObject = new JSONObject();

        try {
            jsonObject.put("name", deal_name);
            jsonObject.put("description", deal_desc);
            jsonObject.put("price", deal_price);
            if (AddPaymentFragment.FLAG_PAYMENT_METHOD) {
                jsonObject.put("cod", "1");
                jsonObject.put("payment_id", "0");
                AddPaymentFragment.FLAG_PAYMENT_METHOD = false;
            } else {
                jsonObject.put("cod", "0");
                jsonObject.put("payment_id", payment_id);
            }

            jsonObject.put("order_time", formattedDate);
            jsonObject.put("user_id", user_id);
            jsonObject.put("quantity",String.valueOf(deal_quantity));
            jsonObject.put("tax",getTax);
            jsonObject.put("sub_total",getFinalPrice);
            jsonObject.put("delivery_fee",delivery_fee);
            jsonObject.put("restaurant_id",res_id);
            jsonObject.put("device","android");
            jsonObject.put("deal_id",deal_id);
            jsonObject.put("version",SplashScreen.VERSION_CODE);
            if(rider_tip.getText().toString().equalsIgnoreCase("Add Rider Tip")){
                jsonObject.put("rider_tip","0");
            }
            else {
                jsonObject.put("rider_tip", rider_tip.getText().toString());
            }
            if(delivery_address_tv.getText().toString().equalsIgnoreCase("Pick Up"))
            {
                jsonObject.put("delivery","0");
                jsonObject.put("address_id", "0");
            }
            else {
                jsonObject.put("delivery","1");
                jsonObject.put("address_id", address_id);
            }


        } catch (JSONException e) {
            e.printStackTrace();
        }

        Log.d("JSONPost", jsonObject.toString());
        Log.d("JSONPost", Config.ORDER_DEAL);

        JsonObjectRequest postOrderJsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.ORDER_DEAL, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                Log.d("JSONPost", response.toString());
                String strJson =  response.toString();
                JSONObject jsonResponse = null;
                try {
                    jsonResponse = new JSONObject(strJson);

                    Log.d("JSONPost", jsonResponse.toString());

                    int code_id  = Integer.parseInt(jsonResponse.optString("code"));

                    if(code_id==401){
                      //  Toast.makeText(getContext(),strJson,Toast.LENGTH_SHORT).show();
                    }
                    else if(code_id == 200) {
                        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                        transparent_layer.setVisibility(View.GONE);
                        progressDialog.setVisibility(View.GONE);
                     //   Toast.makeText(getContext(),"Order Placed",Toast.LENGTH_SHORT).show();


                        //  PagerMainActivity.viewPager.setCurrentItem(1, true);
                        SharedPreferences.Editor editor = deal_order_pref.edit();
                        editor.putString(PreferenceClass.ADDRESS_DELIVERY_FEE,"0").commit();
                        // JSONObject json = new JSONObject(jsonResponse.toString());
                        CartFragment.ORDER_PLACED = true;
                        DEAL_PLACED = true;
                        getActivity().startActivity(new Intent(getContext(),MainActivity.class));
                        getActivity().finish();

                    }

                }catch (JSONException e){

                    e.getCause();
                    Log.d("JSON",e.getMessage().toString());
                }

            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);
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
        postOrderJsonObjectRequest.setRetryPolicy(new DefaultRetryPolicy(5000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));

        postOrderRequestQueue.add(postOrderJsonObjectRequest);
    }

    public void init(View v) {

     /*   cancel_deal_btn = v.findViewById(R.id.cancel_deal_btn);
        back_icon = v.findViewById(R.id.back_icon);

        add_payment_method_div = v.findViewById(R.id.add_payment_method_div);
        delivery_address_div = v.findViewById(R.id.delivery_address_div);
        deals_check_out_div = v.findViewById(R.id.deals_check_out_div);
        pb_deal_order = v.findViewById(R.id.pb_deal_order);

        /// All SharedPref Values
        tax_tv = v.findViewById(R.id.tax_tv);
        deal_name_tv = v.findViewById(R.id.deals_name_tv);
        deal_price_tv = v.findViewById(R.id.deal_price_tv);
        sub_total_price_tv = v.findViewById(R.id.sub_total_price_tv);
        total_tex_tv = v.findViewById(R.id.total_tex_tv);
        total_delivery_fee_tv = v.findViewById(R.id.total_delivery_fee_tv);
        total_deal_order_tv = v.findViewById(R.id.total_deal_order_tv);
        delivery_address_tv = v.findViewById(R.id.delivery_address_tv);
        credit_card_number_tv = v.findViewById(R.id.credit_card_number_tv);

        deal_name = deal_order_pref.getString(PreferenceClass.DELAS_NAME,"");
        deals_tax = deal_order_pref.getString(PreferenceClass.DEALS_TAX,"");
        deal_price = deal_order_pref.getString(PreferenceClass.DEALS_PRICE,"");
        deal_currency = deal_order_pref.getString(PreferenceClass.DEALS_CURRENCY_SYMBOL,"");
        delivery_fee = deal_order_pref.getString(PreferenceClass.DEALS_DELIVERY_FEE,"");
        delivery_address_street = deal_order_pref.getString(PreferenceClass.STREET,"");
        delivery_address_state = deal_order_pref.getString(PreferenceClass.STATE,"");
        delivery_address_city = deal_order_pref.getString(PreferenceClass.CITY,"");
        apartment = deal_order_pref.getString(PreferenceClass.APARTMENT,"");
        user_id = deal_order_pref.getString(PreferenceClass.pre_user_id,"");
        payment_id = deal_order_pref.getString(PreferenceClass.PAYMENT_ID,"");
        address_id = deal_order_pref.getString(PreferenceClass.ADDRESS_ID,"");
       // rest_id = deal_order_pref.getString(Pre)
        card_number = deal_order_pref.getString(PreferenceClass.CREDIT_CARD_ARRAY,"");
        card_brand = deal_order_pref.getString(PreferenceClass.CREDIT_CARD_BRAND,"");
        deal_desc = deal_order_pref.getString(PreferenceClass.DEALS_DESC,"");

        deal_quantity = deal_order_pref.getInt(PreferenceClass.DEALS_QUANTITY,0);
        getFinalPrice = Double.parseDouble(deal_price)*Double.parseDouble(String.valueOf(deal_quantity));



        tax_tv.setText("("+deals_tax+"%)");
        deal_name_tv.setText(deal_name + " (x"+deal_quantity+")");
        deal_price_tv.setText(deal_currency+""+getFinalPrice);
        total_delivery_fee_tv.setText(delivery_fee);
        sub_total_price_tv.setText(deal_currency+""+getFinalPrice);
        getTax = getFinalPrice*Double.parseDouble(deals_tax)/100;
        total_tex_tv.setText(deal_currency+getTax);
        if(delivery_fee.isEmpty()){
            delivery_fee = "0.0";
        }
        total_delivery_fee_tv.setText(deal_currency+delivery_fee);
        Double getTotalOrderFee = getFinalPrice+getTax;
        Double getTotalOrderFeeWithDeliverFee = getTotalOrderFee+Double.parseDouble(delivery_fee);

        total_deal_order_tv.setText(deal_currency+getTotalOrderFeeWithDeliverFee);

        delivery_address_tv.setText(delivery_address_street+", "+apartment+", "+delivery_address_city+", "+delivery_address_state);
        delivery_address_tv.setTextColor(getResources().getColor(R.color.black));

        if(DealsDetailFragment.FLAG_DEALS_DETAIL_FRAGMENT){
            cancel_deal_btn.setVisibility(View.GONE);
            back_icon.setVisibility(View.VISIBLE);
            DealsDetailFragment.FLAG_DEALS_DETAIL_FRAGMENT = false;
        }

            if(AddPaymentFragment.FLAG_PAYMENT_METHOD){
                credit_card_number_tv.setText("Cash on delivery");
                credit_card_number_tv.setTextColor(getResources().getColor(R.color.black));
                AddPaymentFragment.FLAG_PAYMENT_METHOD = false;
            }
            else {
                credit_card_number_tv.setText("**** **** **** " + card_number);
                credit_card_number_tv.setTextColor(getResources().getColor(R.color.black));

            }



        back_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new DealsDetailFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.deal_order_main_container, restaurantMenuItemsFragment,"parent").commit();
                DealsFragment.FLAG_DEAL_FRAGMENT = true;
            }
        });

        delivery_address_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new AddressListFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.deal_order_main_container, restaurantMenuItemsFragment,"parent").commit();
               // FLAG_DELIVER_ADDRESS = true;
                FLAG_DEAL_ORDER = true;

            }
        });

        add_payment_method_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                Fragment restaurantMenuItemsFragment = new AddPaymentFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.deal_order_main_container, restaurantMenuItemsFragment,"parent").commit();
                FLAG_DEAL_ORDER = true;

            }
        });

        deals_check_out_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                Calendar c = Calendar.getInstance();
                System.out.println("Current time =&gt; "+c.getTime());

                SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
                formattedDate = df.format(c.getTime());
                pb_deal_order.setVisibility(View.VISIBLE);

                dealOrder();

            }
        });

    }


   */
    }
}