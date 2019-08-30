package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.app.Dialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;

import android.support.percent.PercentRelativeLayout;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.AlertDialog;
import android.text.InputType;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ExpandableListView;
import android.widget.FrameLayout;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.dinosoftlabs.foodies.android.Adapters.CartFragExpandable;
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.Models.CartFragChildModel;
import com.dinosoftlabs.foodies.android.Models.CartFragParentModel;

import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.CustomExpandableListView;
import com.dinosoftlabs.foodies.android.Utils.TabLayoutUtils;
import com.gmail.samehadar.iosdialog.CamomileSpinner;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.ValueEventListener;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.DecimalFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Collection;
import java.util.HashMap;
import java.util.Map;

import static com.dinosoftlabs.foodies.android.ActivitiesAndFragments.AddressListFragment.CART_NOT_LOAD;

/**
 * Created by Nabeel on 2/12/2018.
 */

public class CartFragment extends Fragment {

    RelativeLayout accept_div,decline_div,cart_payment_method_div,cart_address_div,tip_div,promo_code_div,cart_check_out_div;
    TextView decline_tv,accept_tv,tax_tv,credit_card_number_tv,delivery_address_tv,rider_tip_price_tv,total_delivery_fee_tv,
            promo_tv,total_promo_tv,total_sum_tv,rider_tip,discount_tv,rest_name_tv,free_delivery_tv;
    CustomExpandableListView selected_item_list;
    SharedPreferences sPref;
    DatabaseReference mDatabase;
    FirebaseDatabase firebaseDatabase;
   private static String udid,tax_dues,payment_id,instructions,card_number,riderTip,tax_preference,fee_prefernce,total_sum,res_id,user_id,rest_name,mQuantity,
            coupan_code_;
    String grandTotal_ = "0";

    CartFragExpandable cartFragExpandable;
    ArrayList<CartFragParentModel> listDataHeader;
    ArrayList<CartFragChildModel> listChildData;
    private ArrayList<ArrayList<CartFragChildModel>> ListChild;
    TextView sub_total_price_tv,total_tex_tv;
    String grandTotal,symbol,street,apartment,city,state,address_id;
    public static boolean CART_PAYMENT_METHOD,CART_ADDRESS,CART_LOGIN;
    CamomileSpinner cartProgress;
    Button clear_btn;
    @SuppressWarnings("deprecation")
    PercentRelativeLayout no_cart_div;
    Collection<Object> values;
    Map<String, Object> td;
    HashMap<String,Object> values_final;
    ArrayList<HashMap<String,Object>> extraItemArray;
    private boolean FLAG_COUPON;
    boolean getLoINSession,PICK_UP;
    Double previousRiderTip = 0.0;
    SwipeRefreshLayout refresh;
    private boolean isViewShown = false;
    LinearLayout mainCartDiv;
    JSONArray jsonArrayMenuExtraItem;
    SwipeRefreshLayout swipeRefresh;
    FrameLayout cart_main_container;
    public static boolean ORDER_PLACED,UPDATE_NODE;

    RelativeLayout transparent_layer,progressDialog;

    public static boolean FLAG_CLEAR_ORDER;
    String minimumOrderPrice;
    private static  String key,extraID,mDesc,mGrandTotal,mInstruction,mCurrency,mDesc_,mFee,mName,mPrice,mQuantity_,mTax,
            minimumOrderPrice_,required,restID;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        getActivity().getWindow().setSoftInputMode(
                WindowManager.LayoutParams.SOFT_INPUT_ADJUST_PAN);

        View view = inflater.inflate(R.layout.fragment_cart, container, false);
        if (!isViewShown) {
            initUI(view);
        }
        return view;

    }

    @Override
    public void setUserVisibleHint(boolean isVisibleToUser) {
        super.setUserVisibleHint(isVisibleToUser);

        if (getView() != null) {
            isViewShown = true;
            // fetchdata() contains logic to show data when page is selected mostly asynctask to fill the data
            cart_main_container.invalidate();
            initUI(getView());
        } else {
            isViewShown = false;
        }

    }
    @SuppressWarnings("deprecation")
    public void initUI(View view){
        free_delivery_tv = view.findViewById(R.id.free_delivery_tv);
        progressDialog = view.findViewById(R.id.progressDialog);
        transparent_layer = view.findViewById(R.id.transparent_layer);

        delivery_address_tv = view.findViewById(R.id.delivery_address_tv);
        cartProgress = view.findViewById(R.id.cartProgress);
        cartProgress.start();
        cart_main_container = view.findViewById(R.id.cart_main_container);
        cart_main_container.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                return true;
            }
        });


        cart_main_container.invalidate();
        delivery_address_tv.setText("Select Delivery Address");
        credit_card_number_tv = view.findViewById(R.id.credit_card_number_tv);
        credit_card_number_tv.setText("Select Payment Method");
        sPref = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);
        udid = sPref.getString(PreferenceClass.UDID,"");
        //grandTotal = sPref.getString(PreferenceClass.GRAND_TOTAL,"");
        //  symbol = sPref.getString("symbol","");
        getLoINSession = sPref.getBoolean(PreferenceClass.IS_LOGIN,false);
        extraItemArray = new ArrayList<>();

        // res_id = sPref.getString(PreferenceClass.RESTAURANT_ID,"");
        user_id = sPref.getString(PreferenceClass.pre_user_id,"");

        address_id = sPref.getString(PreferenceClass.ADDRESS_ID,"");
        payment_id = sPref.getString(PreferenceClass.PAYMENT_ID,"");
        rest_name = sPref.getString(PreferenceClass.RESTAURANT_NAME,"");


        firebaseDatabase = FirebaseDatabase.getInstance();
        mDatabase = firebaseDatabase.getReference().child(AllConstants.CALCULATION).child(udid);
        //  mDatabase.keepSynced(true);
        riderTip = "0";
        // tax_preference = sPref.getString(PreferenceClass.RESTAURANT_ITEM_TAX,"");


        no_cart_div = view.findViewById(R.id.no_cart_div);
        mainCartDiv = view.findViewById(R.id.mainCartDiv);
        promo_tv = view.findViewById(R.id.promo_tv);
        total_promo_tv = view.findViewById(R.id.total_promo_tv);

           // mDatabase.keepSynced(true);
            getCartData();


        no_cart_div.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                return true;
            }
        });

        cart_check_out_div = view.findViewById(R.id.cart_check_out_div);

        clear_btn = view.findViewById(R.id.clear_btn);
        rest_name_tv = view.findViewById(R.id.rest_name_tv);

        discount_tv = view.findViewById(R.id.discount_tv);
        promo_code_div = view.findViewById(R.id.promo_code_div);
        rider_tip = view.findViewById(R.id.rider_tip);
        total_sum_tv = view.findViewById(R.id.total_sum_tv);
       // total_deal_order_tv = view.findViewById(R.id.total_deal_order_tv);
        total_delivery_fee_tv= view.findViewById(R.id.total_delivery_fee_tv);
        rider_tip_price_tv = view.findViewById(R.id.rider_tip_price_tv);

        if(rider_tip.getText().toString().equalsIgnoreCase(symbol+" 0")){
            rider_tip.setText("Add Rider Tip");
        }
        tip_div = view.findViewById(R.id.tip_div);

        total_tex_tv = view.findViewById(R.id.total_tex_tv);
        tax_tv = view.findViewById(R.id.tax_tv);

        rest_name_tv.setText(rest_name);

        sub_total_price_tv = view.findViewById(R.id.sub_total_price_tv);
        decline_div = view.findViewById(R.id.decline_div);
        accept_div = view.findViewById(R.id.accept_div);
        decline_tv = view.findViewById(R.id.decline_tv);
        accept_tv = view.findViewById(R.id.accept_tv);
        selected_item_list = view.findViewById(R.id.selected_item_list);




        cart_payment_method_div = view.findViewById(R.id.cart_payment_method_div);
        cart_address_div = view.findViewById(R.id.cart_address_div);

        cart_check_out_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if(delivery_address_tv.getText().toString().equalsIgnoreCase("Select Delivery Address")
                        || credit_card_number_tv.getText().toString().equalsIgnoreCase("Select Payment Method"))
                {
                    Toast.makeText(getContext(),"Delivery Address OR Payment Method is Missed",Toast.LENGTH_LONG).show();
                }else {
                    placeOrder();
                }
            }
        });

        clear_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                showDialogCartDelete();



            }
        });

            if (AddressListFragment.FLAG_ADDRESS_LIST) {
                if(AddressListFragment.FLAG_NO_ADRESS_CHOSE){
                    AddressListFragment.FLAG_NO_ADRESS_CHOSE = false;
                    delivery_address_tv.setText("Select Delivery Address");

                }
                else {
                    street = sPref.getString(PreferenceClass.STREET, "");
                    city = sPref.getString(PreferenceClass.CITY, "");
                    state = sPref.getString(PreferenceClass.STATE, "");
                    apartment = sPref.getString(PreferenceClass.APARTMENT, "");
                    AddressListFragment.FLAG_ADDRESS_LIST = false;
                    AddPaymentFragment.FLAG_ADD_PAYMENT = true;
                    delivery_address_tv.setText(street + " " + city + " " + state);
                }
            }

        if (AddPaymentFragment.FLAG_ADD_PAYMENT) {
            card_number = sPref.getString(PreferenceClass.CREDIT_CARD_ARRAY, "");
            if (AddPaymentFragment.FLAG_CASH_ON_DELIVERY) {
                credit_card_number_tv.setText("Cash on delivery");
                credit_card_number_tv.setTextColor(getResources().getColor(R.color.black));
               // AddPaymentFragment.FLAG_CASH_ON_DELIVERY = false;
              // AddPaymentFragment.FLAG_PAYMENT_METHOD = false;
            } else if(AddPaymentFragment.FLAG_PAYMENT_METHOD = true) {

                if(card_number.isEmpty()){
                    credit_card_number_tv.setText("Cash on delivery");
                 //   AddPaymentFragment.FLAG_PAYMENT_METHOD = false;
                }
                else {
                    credit_card_number_tv.setText("**** **** **** " + card_number);
                 //   AddPaymentFragment.FLAG_PAYMENT_METHOD = false;
                }
                credit_card_number_tv.setTextColor(getResources().getColor(R.color.black));

            }
            AddressListFragment.FLAG_ADDRESS_LIST = true;
            AddPaymentFragment.FLAG_ADD_PAYMENT = false;
        }


        tip_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                addRiderTip();
            }
        });

        promo_code_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                varifyCoupan();
            }
        });

        cart_address_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if(!getLoINSession){
                    Fragment restaurantMenuItemsFragment = new UserAccountFragment();
                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                    transaction.add(R.id.cart_main_container, restaurantMenuItemsFragment,"parent").commit();
                    CART_ADDRESS = true;
                    CART_LOGIN = true;
                }
                else {
                    CART_ADDRESS = true;
                    Fragment restaurantMenuItemsFragment = new AddressListFragment();
                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                    transaction.add(R.id.cart_main_container, restaurantMenuItemsFragment, "parent").commit();
                    SharedPreferences.Editor editor = sPref.edit();
                    editor.putString("grandTotal",grandTotal);
                    editor.putString(PreferenceClass.RESTAURANT_ID,res_id).apply();
                }

            }
        });

        cart_payment_method_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if(!getLoINSession){
                    Fragment restaurantMenuItemsFragment = new UserAccountFragment();
                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                    transaction.add(R.id.cart_main_container, restaurantMenuItemsFragment,"parent").commit();
                    CART_PAYMENT_METHOD = true;
                    CART_LOGIN = true;
                }
                else {
                    CART_PAYMENT_METHOD = true;
                    Fragment restaurantMenuItemsFragment = new AddPaymentFragment();
                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                    transaction.add(R.id.cart_main_container, restaurantMenuItemsFragment, "parent").commit();
                }
            }
        });





        selected_item_list .setExpanded(true);
        selected_item_list.setGroupIndicator(null);

        //  cartExpandableListView.setChoiceMode(ExpandableListView.CHOICE_MODE_SINGLE);

        selected_item_list.setOnGroupClickListener(new ExpandableListView.OnGroupClickListener() {
            @Override
            public boolean onGroupClick(ExpandableListView parent, View v,
                                        int groupPosition, long id) {
                return true; // This way the expander cannot be collapsed
            }
        });


        decline_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                decline_div.setBackground(getResources().getDrawable(R.drawable.round_shape_btn_login));
                accept_div.setBackground(getResources().getDrawable(R.drawable.round_shape_btn_grey));
                decline_tv.setTextColor(getResources().getColor(R.color.colorWhite));
                accept_tv.setTextColor(getResources().getColor(R.color.or_color_name));
                rider_tip_price_tv.setText(symbol+"0");
                total_delivery_fee_tv.setText(symbol+"0");
                rider_tip.setText(symbol+"0");
                delivery_address_tv.setText("Pick Up");
                PICK_UP = true;
                getTotalSumDeliveryFee(fee_prefernce,PICK_UP);
                getTotalSumTip(riderTip,PICK_UP);

                cart_address_div.setOnTouchListener(new View.OnTouchListener() {
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
                rider_tip_price_tv.setText(symbol+riderTip);
                total_delivery_fee_tv.setText(symbol+fee_prefernce);
                rider_tip.setText(symbol+riderTip);
                if(street==null&&apartment==null&&city==null&&state==null){
                    delivery_address_tv.setText("Select Delivery Address");
                }
                else {
                    delivery_address_tv.setText(street + " " + apartment + " " + city + " " + state);
                }
                PICK_UP = false;

                previousRiderTip = 0.0;
                getTotalSumDeliveryFee(fee_prefernce,PICK_UP);
                getTotalSumTip(riderTip,PICK_UP);

                cart_address_div.setOnTouchListener(new View.OnTouchListener() {
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

    public void showDialogCartDelete(){

        AlertDialog.Builder builder;
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            builder = new AlertDialog.Builder(getContext(), android.R.style.Theme_Material_Dialog_Alert);
        } else {
            builder = new AlertDialog.Builder(getContext());
        }
        builder.setTitle("Delete Cart?")
                .setMessage("Are you sure you want to delete cart?")
                .setPositiveButton("Discard", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {
                        // continue with delete
                        mDatabase.setValue(null);
                        no_cart_div.setVisibility(View.VISIBLE);
                        mainCartDiv.setVisibility(View.GONE);
                        SharedPreferences.Editor editor = sPref.edit();
                        editor.putString(PreferenceClass.ADDRESS_DELIVERY_FEE,"0");
                        editor.putInt(PreferenceClass.CART_COUNT,0);
                        editor.putInt("count",0)
                                .commit();
                        Intent intent = new Intent();
                        intent.setAction("AddToCart");
                        getContext().sendBroadcast(intent);

                        rider_tip.setText("Add Rider Tip");
                        discount_tv.setText("Add Promo Code");
                        riderTip = "0";
                        previousRiderTip=Double.parseDouble("0.0");

                        FLAG_CLEAR_ORDER = true;

                        dialog.dismiss();

                    }


                })
                .setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {
                        // do nothing

                        dialog.dismiss();
                    }
                })
                .show();

    }

    @SuppressWarnings("unchecked")
    public void getCartData(){
        mDatabase.keepSynced(true);
        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);
        listDataHeader = new ArrayList<>();
        ListChild = new ArrayList<>();
        DatabaseReference query = mDatabase;
        query.addListenerForSingleValueEvent(new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot dataSnapshot) {
                //child is each element in the finished list
                 td = (HashMap<String, Object>) dataSnapshot.getValue();
              if(td!=null) {

                    values = td.values();
                    String string = values.toString();

                    JSONArray jsonArray = null;
                    try {
                        jsonArray = new JSONArray(values);
                        grandTotal_ = "0";
                        for (int a = 0; a < jsonArray.length(); a++) {

                            JSONObject allJsonObject = jsonArray.getJSONObject(a);

                            CartFragParentModel cartFragParentModel = new CartFragParentModel();

                            cartFragParentModel.setItem_name(allJsonObject.optString("mName"));
                            cartFragParentModel.setItem_price(allJsonObject.optString("mPrice"));
                            mQuantity = allJsonObject.optString("mQuantity");
                            cartFragParentModel.setItem_quantity(allJsonObject.optString("mQuantity"));
                            cartFragParentModel.setItem_symbol(allJsonObject.optString("mCurrency"));
                            cartFragParentModel.setItem_key(allJsonObject.optString("key"));

                            String total = allJsonObject.optString("grandTotal");
                            minimumOrderPrice = allJsonObject.optString("minimumOrderPrice");
                            symbol = allJsonObject.optString("mCurrency");

                            res_id = allJsonObject.optString("restID");

                            if (total.isEmpty() || total.equalsIgnoreCase("null")) {

                                total = "0";
                            }

                            getDescText(minimumOrderPrice,total);

                            grandTotal = String.valueOf(Double.parseDouble(total) + Double.parseDouble(grandTotal_));

                            grandTotal_ = grandTotal;

                            tax_preference = allJsonObject.optString("mTax");
                            instructions = allJsonObject.optString("instruction");
                            //  fee_prefernce = allJsonObject.optString("mFee");


                            listDataHeader.add(cartFragParentModel);
                            listChildData = new ArrayList<>();

                            if (!allJsonObject.has("extraItem")) {
                                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                                transparent_layer.setVisibility(View.GONE);
                                progressDialog.setVisibility(View.GONE);
                                ListChild.add(listChildData);
                            } else {
                                JSONArray extraItemArray = allJsonObject.getJSONArray("extraItem");

                                for (int b = 0; b < extraItemArray.length(); b++) {

                                    JSONObject jsonObject = extraItemArray.getJSONObject(b);

                                    CartFragChildModel cartFragChildModel = new CartFragChildModel();

                                    cartFragChildModel.setQuantity(allJsonObject.optString("mQuantity"));
                                    cartFragChildModel.setSymbol(allJsonObject.optString("mCurrency"));
                                    cartFragChildModel.setName(jsonObject.optString("menu_extra_item_name"));
                                    cartFragChildModel.setPrice(jsonObject.optString("menu_extra_item_price"));

                                    listChildData.add(cartFragChildModel);
                                }
                                ListChild.add(listChildData);

                            }
                        }
                            if(listDataHeader!=null&&listDataHeader.size()>0){

                                (getView().findViewById(R.id.no_cart_div)).setVisibility(View.GONE);
                             //  (getView().findViewById(R.id.no_cart_div)).invalidate();
                               (getView().findViewById(R.id.mainCartDiv)).setVisibility(View.VISIBLE);

                                sub_total_price_tv.setText(symbol+grandTotal);

                                if(!tax_preference.isEmpty()) {
                                    tax_tv.setText("("+tax_preference+"%)");
                                }
                                else {
                                    tax_preference = String.valueOf(0);
                                    tax_tv.setText("(0%)");
                                }

                                fee_prefernce = sPref.getString(PreferenceClass.ADDRESS_DELIVERY_FEE,"");
                                String prefFee = fee_prefernce;

                                if(fee_prefernce!=null) {
                                    if (fee_prefernce.isEmpty()) {
                                        fee_prefernce = String.valueOf(0);
                                    }
                                }
                                if (fee_prefernce==null){
                                    fee_prefernce="0";
                                }



                                if (grandTotal.isEmpty()){
                                    grandTotal="0.0";
                                }
                                tax_dues = String.valueOf(Double.parseDouble(grandTotal)*Double.parseDouble(tax_preference)/100);
                                total_tex_tv.setText(symbol+tax_dues);

                                if(delivery_address_tv.getText().toString().equalsIgnoreCase("Select Delivery Address")){
                                    fee_prefernce = ""+0.0;
                                }
                                total_delivery_fee_tv.setText(symbol+fee_prefernce);
                                // Getting Total Sum
                                total_sum = String.valueOf(Double.valueOf(Double.parseDouble(grandTotal)+Double.parseDouble(tax_dues)+Double.parseDouble(fee_prefernce)));

                                //getTotalSumTip("0",PICK_UP);

                                rider_tip_price_tv.setText(symbol+"0.0");

                                total_promo_tv.setText(symbol+"0.0");
                                total_sum_tv.setText(total_sum);


                                cartFragExpandable = new CartFragExpandable(getContext(), listDataHeader, ListChild);
                                selected_item_list.setAdapter(cartFragExpandable);
                                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                                transparent_layer.setVisibility(View.GONE);
                                progressDialog.setVisibility(View.GONE);
                                int itemCount = cartFragExpandable.getGroupCount();


                                for(int i=0; i < cartFragExpandable.getGroupCount(); i++)
                                    try {

                                        selected_item_list.expandGroup(i);
                                    }
                                    catch (IndexOutOfBoundsException e){
                                        e.getCause();
                                    }

                                selected_item_list.setOnGroupClickListener(new ExpandableListView.OnGroupClickListener() {
                                    @Override
                                    public boolean onGroupClick(ExpandableListView parent, View v, int groupPosition, long id) {

                                        CartFragParentModel item = (CartFragParentModel) listDataHeader.get(groupPosition);

                                        key = item.getItem_key();

                                        customDialogbox();

                                        return true;
                                    }
                                });

                            }
                            else {
                                (getView().findViewById(R.id.no_cart_div)).setVisibility(View.VISIBLE);
                               // (getView().findViewById(R.id.no_cart_div)).invalidate();
                                (getView().findViewById(R.id.mainCartDiv)).setVisibility(View.GONE);
                            }




                    } catch (JSONException e) {
                        e.printStackTrace();
                        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                        transparent_layer.setVisibility(View.GONE);
                        progressDialog.setVisibility(View.GONE);
                        (getView().findViewById(R.id.no_cart_div)).setVisibility(View.VISIBLE);
                        (getView().findViewById(R.id.mainCartDiv)).setVisibility(View.GONE);
                    }
                }
                else {
                  TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                  transparent_layer.setVisibility(View.GONE);
                  progressDialog.setVisibility(View.GONE);
                  (getView().findViewById(R.id.no_cart_div)).setVisibility(View.VISIBLE);
                  (getView().findViewById(R.id.mainCartDiv)).setVisibility(View.GONE);
                 // (getView().findViewById(R.id.no_cart_div)).invalidate();
                }
            }

            @Override
            public void onCancelled(DatabaseError databaseError) {
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);
                (getView().findViewById(R.id.no_cart_div)).setVisibility(View.VISIBLE);
                (getView().findViewById(R.id.mainCartDiv)).setVisibility(View.GONE);

            }
        });

    }



    private void getDescText(String minimumOrderPrice,String grandTotal){

        Double var3 = Double.parseDouble(minimumOrderPrice)-Double.parseDouble(grandTotal);

        if(var3>=Double.parseDouble(minimumOrderPrice)){

            free_delivery_tv.setText("You have reached your free delivery order.");


        }
        else {
            if(String.valueOf(var3).contains("-")){
                free_delivery_tv.setText("You have reached your free delivery order.");
            }
            else {
                free_delivery_tv.setText("You have to need more "+symbol+var3+" for free delivery order.");
            }
        }


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
                rider_tip_price_tv.setText(symbol+riderTip);
                rider_tip.setText(symbol+riderTip);
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

    public void varifyCoupan(){

        // custom dialog
        final Dialog dialog = new Dialog(getContext());
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        dialog.setContentView(R.layout.custom_dialog_cart);


       final EditText ed_text = dialog.findViewById(R.id.ed_text);

        // set the custom dialog components - text, image and button

        Button cancelDiv = (Button) dialog.findViewById(R.id.cancel_btn);
        Button done_btn = (Button) dialog.findViewById(R.id.done_btn);

        done_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                coupan_code_ = ed_text.getText().toString();
                getCoupanRequest(coupan_code_);

                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
                transparent_layer.setVisibility(View.VISIBLE);
                progressDialog.setVisibility(View.VISIBLE);
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

    public void getCoupanRequest(String coupan_code){

        RequestQueue queue = Volley.newRequestQueue(getContext());

        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("coupon_code",coupan_code);
            jsonObject.put("restaurant_id",res_id);
            jsonObject.put("user_id",user_id);

        } catch (JSONException e) {
            e.printStackTrace();
        }

        Log.d("JSONPost", jsonObject.toString());


        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.VERIFY_COUPAN, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                String string = response.toString();

                Log.d("JSONPost", response.toString());

                try {
                    JSONObject jsonObject1 = new JSONObject(string);

                    int code = Integer.parseInt(jsonObject1.optString("code"));
                    if(FLAG_COUPON){
                        Toast.makeText(getContext(),"Coupon Already Been Aded",Toast.LENGTH_SHORT).show();
                        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                        transparent_layer.setVisibility(View.GONE);
                        progressDialog.setVisibility(View.GONE);
                    }
                    else {
                        if (code == 200) {
                            FLAG_COUPON = true;
                            JSONArray jsonArray = jsonObject1.getJSONArray("msg");
                            for (int i = 0; i < jsonArray.length(); i++) {

                                JSONObject jsonObject2 = jsonArray.getJSONObject(i);

                                JSONObject jsonObject3 = jsonObject2.getJSONObject("RestaurantCoupon");
                                String discount = jsonObject3.optString("discount");

                                // riderTip = edittext.getText().toString();


                                promo_tv.setText("("+discount+"%)");
                                getTotalSumCoupon(discount,symbol);
                              //  discount_tv.setText(symbol + discount);
                                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                                transparent_layer.setVisibility(View.GONE);
                                progressDialog.setVisibility(View.GONE);
                              //  no_cart_div.setVisibility(View.VISIBLE);
                               // mainCartDiv.setVisibility(View.GONE);
                               // mDatabase.keepSynced(true);
                            }

                            //rider_tip.setText(symbol+riderTip);
                        } else {
                            Toast.makeText(getContext(), response.toString(), Toast.LENGTH_SHORT).show();
                            TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                            transparent_layer.setVisibility(View.GONE);
                            progressDialog.setVisibility(View.GONE);
                        }
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                    TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                    transparent_layer.setVisibility(View.GONE);
                    progressDialog.setVisibility(View.GONE);

                }

            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);

                Log.d("Volly Error", error.toString());
               // Toast.makeText(getContext(),error.toString(),Toast.LENGTH_SHORT).show();

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

        queue.add(jsonObjectRequest);

    }


    public void getTotalSumDeliveryFee(String deliveryFee,boolean picu_up){

        if(picu_up){
            total_sum = String.valueOf(Double.parseDouble(total_sum)-Double.parseDouble(deliveryFee));
        }
        else {
            total_sum = String.valueOf(Double.parseDouble(total_sum) + Double.parseDouble(deliveryFee));
        }
        total_sum_tv.setText(symbol+new DecimalFormat("##.##").format(Double.parseDouble(total_sum)));

    }
    public void getTotalSumTip(String riderTip,boolean rider_tip_pick_up){
        if(rider_tip_pick_up){
            total_sum = String.valueOf(Double.parseDouble(total_sum)-Double.parseDouble(riderTip));
        }
        else {

            total_sum = String.valueOf(Double.parseDouble(total_sum) + Double.parseDouble(riderTip));
            total_sum = String.valueOf(Double.parseDouble(total_sum)-previousRiderTip);
            previousRiderTip = Double.parseDouble(riderTip);

        }
        total_sum_tv.setText(symbol+new DecimalFormat("##.##").format(Double.parseDouble(total_sum)));

    }

    public void getTotalSumCoupon(String discount,String symbol){

        Double total_discount = Double.valueOf(new DecimalFormat("##.##").format(Double.parseDouble(discount)/100*Double.parseDouble(grandTotal_)));

        discount_tv.setText(symbol+" "+total_discount+" ("+discount+"%)");
        total_promo_tv.setText(symbol+" "+total_discount);

        total_sum = String.valueOf(Double.parseDouble(total_sum)-total_discount);

        total_sum_tv.setText(symbol+new DecimalFormat("##.##").format(Double.parseDouble(total_sum)));

    }

    public void placeOrder(){
        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);
        JSONArray menu_item=null;
        JSONArray valueArray = new JSONArray(values);
        for (int i=0;i<valueArray.length();i++){

            JSONObject jsonObject1 = null;
            try {
                jsonObject1 = valueArray.getJSONObject(i);
                values_final= new HashMap<>();

                if(jsonObject1.optString("extraItem")!=null&& !jsonObject1.optString("extraItem").isEmpty()) {
                    jsonArrayMenuExtraItem = new JSONArray(jsonObject1.optString("extraItem"));
                    values_final.put("menu_extra_item",jsonArrayMenuExtraItem);
                    String size = String.valueOf(jsonArrayMenuExtraItem.length());
                }
                else {
                    values_final.put("menu_extra_item",new JSONArray("["+"]"));
                }


                    values_final.put("menu_item_price", jsonObject1.optString("mPrice"));
                    values_final.put("menu_item_quantity", jsonObject1.optString("mQuantity"));
                    values_final.put("menu_item_name", jsonObject1.optString("mName"));


                     extraItemArray.add(values_final);

            } catch (JSONException e) {
                e.printStackTrace();
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);

            }

        }

        //JSONObject obj=new JSONObject(values_final);
        menu_item =new JSONArray(extraItemArray);

        Calendar c = Calendar.getInstance();
        System.out.println("Current time => "+c.getTime());

        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        String formattedDate = df.format(c.getTime());



        RequestQueue queue = Volley.newRequestQueue(getContext());

        JSONObject jsonObject = new JSONObject();
        try {

            jsonObject.put("user_id",user_id);
            jsonObject.put("price",total_sum);
            jsonObject.put("sub_total",grandTotal);
            jsonObject.put("tax",tax_dues);
            jsonObject.put("quantity",mQuantity);
            if(delivery_address_tv.getText().toString().equalsIgnoreCase("Pick Up"))
            {
                jsonObject.put("address_id", "");
            }else {
                jsonObject.put("address_id", address_id);
            }
            jsonObject.put("restaurant_id",res_id);
            jsonObject.put("instructions",instructions);
            jsonObject.put("coupon_id","0");
            jsonObject.put("order_time",formattedDate);
            jsonObject.put("delivery_fee",fee_prefernce);
            jsonObject.put("version",SplashScreen.VERSION_CODE);

            if(delivery_address_tv.getText().toString().equalsIgnoreCase("Pick Up"))
            {
                jsonObject.put("delivery","0");
            }
            else {
                jsonObject.put("delivery","1");
            }

            if(rider_tip.getText().toString().equalsIgnoreCase("Add Rider Tip")){
                jsonObject.put("rider_tip","0");
            }
            else {
                String riderTip_ = riderTip;
                jsonObject.put("rider_tip",riderTip_ );
            }

            jsonObject.put("device","android");


            if(credit_card_number_tv.getText().toString().equalsIgnoreCase("Cash on delivery")){
            jsonObject.put("cod","1");
            jsonObject.put("payment_id","0");
            }
            else {
                jsonObject.put("cod","0");
                jsonObject.put("payment_id",payment_id);
            }

            jsonObject.put("menu_item",menu_item);
            String str = menu_item.toString();



        } catch (JSONException e) {
            e.printStackTrace();
            TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
            transparent_layer.setVisibility(View.GONE);
            progressDialog.setVisibility(View.GONE);

        }

        Log.d("JSONPost", jsonObject.toString());


        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.PLACE_ORDER, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                Log.d("JSONPost", response.toString());


                String str = response.toString();
                try {
                    JSONObject jsonObject1 = new JSONObject(str);
                    int code = Integer.parseInt(jsonObject1.optString("code"));
                    if(code==401){
                        Toast.makeText(getContext(),str,Toast.LENGTH_SHORT).show();
                    }

                    else if (code==200) {
                        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                        transparent_layer.setVisibility(View.GONE);
                        progressDialog.setVisibility(View.GONE);
                     //   Toast.makeText(getContext(),"Order Placed",Toast.LENGTH_SHORT).show();
                        mDatabase.setValue(null);
                        Intent intent = new Intent();
                        intent.setAction("AddToCart");
                        getContext().sendBroadcast(intent);

                      //  PagerMainActivity.viewPager.setCurrentItem(1, true);
                        SharedPreferences.Editor editor = sPref.edit();
                        editor.putString(PreferenceClass.ADDRESS_DELIVERY_FEE,"0");
                        editor.putInt(PreferenceClass.CART_COUNT,0);
                        editor.putInt("count",0)
                                .commit();
                        ORDER_PLACED = true;

                        FLAG_CLEAR_ORDER = true;
                        OrderDetailFragment.CALLBACK_ORDERFRAG = true;

                        getCartData();

                        startActivity(new Intent(getContext(),MainActivity.class));
                        getActivity().finish();

                    }

                    else {
                        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                        transparent_layer.setVisibility(View.GONE);
                        progressDialog.setVisibility(View.GONE);
                        Toast.makeText(getContext(),"Your selected address not match with your city.",Toast.LENGTH_SHORT).show();
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                    TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                    transparent_layer.setVisibility(View.GONE);
                    progressDialog.setVisibility(View.GONE);


                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);
                Toast.makeText(getContext(),error.toString(),Toast.LENGTH_SHORT).show();

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

        jsonObjectRequest.setRetryPolicy(new DefaultRetryPolicy(5000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        queue.add(jsonObjectRequest);
    }


    public void customDialogbox(){

        // custom dialog
        final Dialog dialog = new Dialog(getContext());
        dialog.setContentView(R.layout.custom_dialoge_box);

        // set the custom dialog components - text, image and button

        RelativeLayout cancelDiv = (RelativeLayout) dialog.findViewById(R.id.forth);
        RelativeLayout currentOrderDiv = (RelativeLayout) dialog.findViewById(R.id.second);
        RelativeLayout pastOrderDiv = (RelativeLayout) dialog.findViewById(R.id.third);
        TextView first_tv = (TextView)dialog.findViewById(R.id.first_tv);
        TextView second_tv = (TextView)dialog.findViewById(R.id.second_tv);
        TextView third_tv = (TextView)dialog.findViewById(R.id.third_tv);
        first_tv.setText("Edit");
        first_tv.setTextColor(getResources().getColor(R.color.colorFB));
        second_tv.setText("Delete");
        second_tv.setTextColor(getResources().getColor(R.color.colorRed));
        third_tv.setTextColor(getResources().getColor(R.color.colorFB));

        currentOrderDiv.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                editNode();

                UPDATE_NODE = true;

                dialog.dismiss();

            }
        });

        pastOrderDiv.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                deleteSelectedNode(key);
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


    @Override
    public void onResume() {
        super.onResume();
        if(CART_NOT_LOAD ){
            CART_NOT_LOAD = false;
        }
        else {
            getCartData();
        }
    }

    public void deleteSelectedNode(final String key){

       final DatabaseReference deleteNode = mDatabase.child(key);

       deleteNode.addListenerForSingleValueEvent(new ValueEventListener() {
           @Override
           public void onDataChange(DataSnapshot dataSnapshot) {

               String name = dataSnapshot.child("key").getValue(String.class);


                   if(name.equalsIgnoreCase(key)){
                       deleteNode.setValue(null);
                       getCartData();

                       int getCartCount = sPref.getInt("count",0);

                       SharedPreferences.Editor editor = sPref.edit();
                       editor.putInt("count",getCartCount-1).commit();
                       getActivity().sendBroadcast(new Intent("AddToCart"));

                   }

           }

           @Override
           public void onCancelled(DatabaseError databaseError) {

           }
       });

    }

    public void editNode(){

        final DatabaseReference deleteNode = mDatabase.child(key);

        deleteNode.addListenerForSingleValueEvent(new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot dataSnapshot) {

                String name = dataSnapshot.child("key").getValue(String.class);

                if(name.equalsIgnoreCase(key)){

                    extraID = dataSnapshot.child("mID").getValue(String.class);
                    mDesc = dataSnapshot.child("mDesc").getValue(String.class);
                    mGrandTotal = dataSnapshot.child("grandTotal").getValue(String.class);
                    mInstruction =dataSnapshot.child("instruction").getValue(String.class);
                    mCurrency = dataSnapshot.child("mCurrency").getValue(String.class);
                    mDesc_ = dataSnapshot.child("mDesc").getValue(String.class);
                    mFee = dataSnapshot.child("mFee").getValue(String.class);
                    mName = dataSnapshot.child("mName").getValue(String.class);
                    mPrice = dataSnapshot.child("mPrice").getValue(String.class);
                    mQuantity_ = dataSnapshot.child("mQuantity").getValue(String.class);
                    mTax = dataSnapshot.child("mTax").getValue(String.class);
                    minimumOrderPrice_ = dataSnapshot.child("minimumOrderPrice").getValue(String.class);
                    required = dataSnapshot.child("required").getValue(String.class);
                    restID = dataSnapshot.child("restID").getValue(String.class);

                    Intent intent = new Intent(getContext(),AddToCartActivity.class);
                    intent.putExtra("extra_id",extraID );
                    intent.putExtra("desc",mDesc);
                    intent.putExtra("name",mName);
                    intent.putExtra("price",mPrice);
                    intent.putExtra("symbol",mCurrency);
                    intent.putExtra("key",key);
                    startActivity(intent);

                }

            }

            @Override
            public void onCancelled(DatabaseError databaseError) {

            }
        });



    }

}
