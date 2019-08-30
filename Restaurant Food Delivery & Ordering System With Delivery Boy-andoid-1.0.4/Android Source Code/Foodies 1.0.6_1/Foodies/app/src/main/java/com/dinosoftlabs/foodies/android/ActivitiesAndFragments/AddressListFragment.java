package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.content.Context;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.RelativeLayout;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.dinosoftlabs.foodies.android.Adapters.AddressListAdapter;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.Functions;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.Models.AddressListModel;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.TabLayoutUtils;
import com.gmail.samehadar.iosdialog.CamomileSpinner;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;


/**
 * Created by Nabeel on 1/3/2018.
 */

public class AddressListFragment extends Fragment {

    Button cancel;
    ImageView back_icon;
    RelativeLayout add_address_div;
    public static boolean FLAG_ADDRESS_LIST,FLAG_NO_ADRESS_CHOSE,CART_NOT_LOAD;
    SharedPreferences sharedPreferences;

    ArrayList<AddressListModel> arrayListAddress;
    RecyclerView.LayoutManager recyclerViewlayoutManager;
    AddressListAdapter recyclerViewadapter;
    RecyclerView recycler_view;
    CamomileSpinner addresListProgress;
    FrameLayout address_list_container;

    RelativeLayout transparent_layer,progressDialog;


    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.activity_add_address, container, false);
        sharedPreferences = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);
        address_list_container = v.findViewById(R.id.address_list_container);
        address_list_container.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                return true;
            }
        });


     /*   getFragmentManager()
                .beginTransaction()
                .replace(R.id.content, fragment2)
                .commit();*/
        initUI(v);

        getAddressList();

        return v;
    }

    public void initUI(View v){

        progressDialog = v.findViewById(R.id.progressDialog_address);
        transparent_layer = v.findViewById(R.id.transparent_layer_address);
        addresListProgress = v.findViewById(R.id.addresListProgress);
        addresListProgress.start();
        recycler_view = v.findViewById(R.id.list_address);
        recyclerViewlayoutManager = new LinearLayoutManager(getContext());
        recycler_view.setLayoutManager(recyclerViewlayoutManager);

        cancel = v.findViewById(R.id.cancle_address_btn);
        back_icon = v.findViewById(R.id.back_icon);
        add_address_div = v.findViewById(R.id.add_address_div);

        add_address_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                Fragment restaurantMenuItemsFragment = new AddressDetailFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.address_list_container, restaurantMenuItemsFragment,"parent").commit();
                FLAG_ADDRESS_LIST = true;

              /*  if (mGoogleApiClient2 != null && mGoogleApiClient2.isConnected()) {
                    mGoogleApiClient2.stopAutoManage((FragmentActivity) getContext());
                    mGoogleApiClient2.disconnect();
                }*/

            }
        });

        if(UserAccountFragment.FLAG_DELIVER_ADDRESS || DealOrderFragment.DEAL_ADDRESS||CartFragment.CART_ADDRESS){
            back_icon.setVisibility(View.VISIBLE);
            cancel.setVisibility(View.GONE);
            UserAccountFragment.FLAG_DELIVER_ADDRESS = false;
            back_icon.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {

                    Functions.Hide_keyboard(getActivity());


                    FLAG_NO_ADRESS_CHOSE = true;
                    if(DealOrderFragment.DEAL_ADDRESS){
                        DealOrderFragment userAccountFragment = new DealOrderFragment();
                        FragmentTransaction transaction = getFragmentManager().beginTransaction();
                        transaction.replace(R.id.address_list_container, userAccountFragment);
                        transaction.addToBackStack(null);
                        transaction.commit();
                        DealOrderFragment.DEAL_ADDRESS = false;
                        DealsDetailFragment.FLAG_DEALS_DETAIL_FRAGMENT =true;
                    }
                    else if(CartFragment.CART_ADDRESS){

                        CartFragment userAccountFragment = new CartFragment();
                        FragmentTransaction transaction = getFragmentManager().beginTransaction();
                        transaction.replace(R.id.address_list_container, userAccountFragment);
                        transaction.addToBackStack(null);
                        transaction.commit();
                        CartFragment.CART_ADDRESS = false;
                        FLAG_ADDRESS_LIST = true;
                        CART_NOT_LOAD = true;

                    }
                    else {
                        UserAccountFragment userAccountFragment = new UserAccountFragment();
                        FragmentTransaction transaction = getFragmentManager().beginTransaction();
                        transaction.replace(R.id.address_list_container, userAccountFragment);
                        transaction.addToBackStack(null);
                        transaction.commit();
                    }
                }
            });

        }

    }

    public void getAddressList(){
        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);
        arrayListAddress = new ArrayList<>();
        String user_id = sharedPreferences.getString(PreferenceClass.pre_user_id,"");
        String res_id = sharedPreferences.getString(PreferenceClass.RESTAURANT_ID,"");
        String sub_total = sharedPreferences.getString("grandTotal","");

        //Creating a string request
        RequestQueue queue = Volley.newRequestQueue(getContext());


        JSONObject addressJsonObject = new JSONObject();

        try {
            if(UserAccountFragment.FLAG_DELIVER_ADDRESS) {
                addressJsonObject.put("user_id", user_id);
                UserAccountFragment.FLAG_DELIVER_ADDRESS = false;
            }
            else {
                addressJsonObject.put("user_id", user_id);
                addressJsonObject.put("restaurant_id", res_id);
                addressJsonObject.put("sub_total", sub_total);
            }

        } catch (JSONException e) {
            e.printStackTrace();
        }

        Log.d("JSONPost",addressJsonObject.toString());

        JsonObjectRequest addressJsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.GET_DELIVERY_ADDRESES, addressJsonObject,
                new Response.Listener<JSONObject>() {
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
                                JSONArray jsonarray = json.getJSONArray("msg");

                                for (int i=0;i<jsonarray.length();i++){

                                    JSONObject addressJson = jsonarray.getJSONObject(i);
                                    JSONObject jsonObjAdd = addressJson.getJSONObject("Address");

                                    AddressListModel addressListModel = new AddressListModel();

                                    if(jsonObjAdd.optString("apartment").isEmpty()){
                                        addressListModel.setApartment("");
                                    }
                                    else {
                                        addressListModel.setApartment(jsonObjAdd.optString("apartment"));
                                    }

                                    if(jsonObjAdd.optString("city").isEmpty()){
                                        addressListModel.setCity("");
                                    }
                                    else {
                                        addressListModel.setCity(jsonObjAdd.optString("city"));
                                    }
                                    if(jsonObjAdd.optString("state").isEmpty()){
                                        addressListModel.setState("");
                                    }
                                    else {
                                        addressListModel.setState(jsonObjAdd.optString("state"));
                                    }
                                    if(jsonObjAdd.optString("street").isEmpty()){
                                        addressListModel.setStreet("");
                                    }
                                    else {
                                        addressListModel.setStreet(jsonObjAdd.optString("street"));
                                    }
                                    addressListModel.setAddress_id(jsonObjAdd.optString("id"));
                                    addressListModel.setDelivery_fee(jsonObjAdd.optString("delivery_fee"));
                                    String del = jsonObjAdd.optString("delivery_fee");


                                    arrayListAddress.add(addressListModel);

                                }

                                if(arrayListAddress!=null) {
                                    TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                                    transparent_layer.setVisibility(View.GONE);
                                    progressDialog.setVisibility(View.GONE);
                                    recyclerViewadapter = new AddressListAdapter(arrayListAddress, getActivity());
                                    recycler_view.setAdapter(recyclerViewadapter);
                                    recyclerViewadapter.notifyDataSetChanged();
                                    recyclerViewadapter.setOnItemClickListner(new AddressListAdapter.OnItemClickListner() {
                                        @Override
                                        public void OnItemClicked(View view, int position) {
                                            SharedPreferences.Editor editor = sharedPreferences.edit();
                                            editor.putString(PreferenceClass.STREET,arrayListAddress.get(position).getStreet());
                                            editor.putString(PreferenceClass.CITY,arrayListAddress.get(position).getCity());
                                            editor.putString(PreferenceClass.STATE,arrayListAddress.get(position).getState());
                                            editor.putString(PreferenceClass.APARTMENT,arrayListAddress.get(position).getApartment());
                                            editor.putString(PreferenceClass.ADDRESS_ID,arrayListAddress.get(position).getAddress_id());
                                            editor.putString(PreferenceClass.ADDRESS_DELIVERY_FEE,arrayListAddress.get(position).getDelivery_fee());

                                            editor.commit();

                                            Bundle bundle = new Bundle();
                                            bundle.putString("street",arrayListAddress.get(position).getStreet());
                                            bundle.putString("apartment",arrayListAddress.get(position).getApartment());
                                            bundle.putString("city",arrayListAddress.get(position).getCity());
                                            bundle.putString("state",arrayListAddress.get(position).getState());// Put anything what you want

                                            CartFragment fragment2 = new CartFragment();
                                            fragment2.setArguments(bundle);

                                            if(DealOrderFragment.DEAL_ADDRESS){
                                                DealOrderFragment userAccountFragment = new DealOrderFragment();
                                                FragmentTransaction transaction = getFragmentManager().beginTransaction();
                                                transaction.replace(R.id.address_list_container, userAccountFragment);
                                                transaction.addToBackStack(null);
                                                transaction.commit();
                                                DealOrderFragment.DEAL_ADDRESS = false;
                                                DealOrderFragment.FLAG_DEAL_ADDRESS =true;
                                              //  AddPaymentFragment.FLAG_PAYMENT_METHOD = true;
                                            }

                                            else if(CartFragment.CART_ADDRESS){
                                                CartFragment userAccountFragment = new CartFragment();
                                                FragmentTransaction transaction = getFragmentManager().beginTransaction();
                                                transaction.replace(R.id.address_list_container, userAccountFragment);
                                                transaction.addToBackStack(null);
                                                transaction.commit();
                                                FLAG_ADDRESS_LIST = true;
                                                CartFragment.CART_ADDRESS = false;
                                               AddPaymentFragment.FLAG_PAYMENT_METHOD = false;
                                                CART_NOT_LOAD = true;

                                            }


                                        }
                                    });
                                }

                            }

                        }
                        catch (JSONException e){
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
              //  Toast.makeText(getContext(), "Error: " +error.getMessage(), Toast.LENGTH_SHORT).show();
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

        addressJsonObjectRequest.setRetryPolicy(new DefaultRetryPolicy(
                35000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        queue.add(addressJsonObjectRequest);

    }
}
