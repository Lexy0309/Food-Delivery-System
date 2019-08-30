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
import android.view.View;
import android.view.ViewGroup;
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
import com.dinosoftlabs.foodies.android.Adapters.CreditCardDetailAdapter;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.Models.CardDetailModel;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.TabLayoutUtils;
import com.gmail.samehadar.iosdialog.CamomileSpinner;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import static com.dinosoftlabs.foodies.android.ActivitiesAndFragments.AddressListFragment.CART_NOT_LOAD;

/**
 * Created by Nabeel on 1/1/2018.
 */

public class AddPaymentFragment extends Fragment {

    RelativeLayout add_payment_method_div,cash_on_delivery_div;
    ImageView back_icon;
    public static boolean FLAG_FRAGMENT,FLAG_PAYMENT_METHOD,FLAG_CASH_ON_DELIVERY,FLAG_ADD_PAYMENT;

    RecyclerView.LayoutManager recyclerViewlayoutManager;
    CreditCardDetailAdapter recyclerViewadapter;
    RecyclerView card_recycler_view;
    SharedPreferences sharedPreferences;

    ArrayList<CardDetailModel> cardDetailModelArrayList;
    CamomileSpinner pbHeaderProgress;

    RelativeLayout transparent_layer,progressDialog;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.add_payment_fragment, container, false);
        sharedPreferences = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);

        initUI(v);
        getPaymentList();


        return v;
    }


    public void initUI(View v){
        progressDialog = v.findViewById(R.id.progressDialog_payment);
        transparent_layer = v.findViewById(R.id.transparent_layer_payment);
        cash_on_delivery_div = v.findViewById(R.id.cash_on_delivery_div);
        add_payment_method_div = v.findViewById(R.id.add_payment_method_div);
        pbHeaderProgress = v.findViewById(R.id.paymentListProgress);
        pbHeaderProgress.start();
        back_icon=v.findViewById(R.id.back_icon);
        card_recycler_view = v.findViewById(R.id.paymenth_recycler);
        recyclerViewlayoutManager = new LinearLayoutManager(getContext());
        card_recycler_view.setLayoutManager(recyclerViewlayoutManager);

      /*  if(AddPaymentDetailFragment.cardetailArraylist!=null) {
            recyclerViewadapter = new CreditCardDetailAdapter(AddPaymentDetailFragment.cardetailArraylist, getActivity());
            card_recycler_view.setAdapter(recyclerViewadapter);
            recyclerViewadapter.notifyDataSetChanged();
        }
*/


        add_payment_method_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                Fragment restaurantMenuItemsFragment = new AddPaymentDetailFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.add_payment_main_container, restaurantMenuItemsFragment,"parent").commit();

                FLAG_FRAGMENT = true;
            }
        });

        if(DealOrderFragment.DEAL_PAYMENT_METHOD||CartFragment.CART_PAYMENT_METHOD){
            cash_on_delivery_div.setVisibility(View.VISIBLE);
          //  DealOrderFragment.FLAG_DEAL_ORDER = false;
          //  DealsDetailFragment.FLAG_DEALS_DETAIL_FRAGMENT=true;


        }
        else {
            cash_on_delivery_div.setVisibility(View.GONE);
        }

        back_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if(DealOrderFragment.DEAL_PAYMENT_METHOD){
                    DealOrderFragment userAccountFragment = new DealOrderFragment();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();
                    transaction.replace(R.id.add_payment_main_container, userAccountFragment);
                    transaction.addToBackStack(null);
                    transaction.commit();
                    cash_on_delivery_div.setVisibility(View.VISIBLE);
                    DealOrderFragment.DEAL_PAYMENT_METHOD = false;
                    DealsDetailFragment.FLAG_DEALS_DETAIL_FRAGMENT=true;

                }
                else  if(CartFragment.CART_PAYMENT_METHOD) {
                    CartFragment userAccountFragment = new CartFragment();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();
                    transaction.replace(R.id.add_payment_main_container, userAccountFragment);
                    transaction.addToBackStack(null);
                    transaction.commit();
                    CartFragment.CART_PAYMENT_METHOD = false;
                    FLAG_ADD_PAYMENT = true;
                    CART_NOT_LOAD = true;

                }
                else {
                    UserAccountFragment userAccountFragment = new UserAccountFragment();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();
                    transaction.replace(R.id.add_payment_main_container, userAccountFragment);
                    transaction.addToBackStack(null);
                    transaction.commit();
                }
            }
        });

        cash_on_delivery_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if(CartFragment.CART_PAYMENT_METHOD){
                    CartFragment userAccountFragment = new CartFragment();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();
                    transaction.replace(R.id.add_payment_main_container, userAccountFragment);
                    transaction.addToBackStack(null);
                    transaction.commit();
                    CartFragment.CART_PAYMENT_METHOD = false;
                    FLAG_ADD_PAYMENT = true;
                    AddressListFragment.FLAG_ADDRESS_LIST = false;
                    CART_NOT_LOAD = true;

                    FLAG_CASH_ON_DELIVERY = true;
                    FLAG_PAYMENT_METHOD = false;
                }
                else {
                    DealOrderFragment userAccountFragment = new DealOrderFragment();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();
                    transaction.replace(R.id.add_payment_main_container, userAccountFragment);
                    transaction.addToBackStack(null);
                    transaction.commit();
                    FLAG_PAYMENT_METHOD = true;

                    DealOrderFragment.FLAG_DEAL_ORDER = false;
                    DealsDetailFragment.FLAG_DEALS_DETAIL_FRAGMENT = true;
                }
            }
        });


    }

    public void getPaymentList()  {

        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);
        cardDetailModelArrayList = new ArrayList<>();

        RequestQueue paymentRequestQueue = Volley.newRequestQueue(getContext());

        String user_id = sharedPreferences.getString(PreferenceClass.pre_user_id,"");

        JSONObject paymentJsonObject = new JSONObject();

        try {
            paymentJsonObject.put("user_id",user_id);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest payJsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.GET_PAYMENT_METHID, paymentJsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                Log.d("JSONPost", response.toString());
                String strJson =  response.toString();
                JSONObject jsonResponse = null;
                try {
                    jsonResponse = new JSONObject(strJson);

                    Log.d("JSONPost", jsonResponse.toString());

                    int code_id  = Integer.parseInt(jsonResponse.optString("code"));
                    int code_no_record = Integer.parseInt(jsonResponse.optString("code"));

                    if (code_no_record==201){

                        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                        transparent_layer.setVisibility(View.GONE);
                        progressDialog.setVisibility(View.GONE);
                          //  Toast.makeText(getContext(),strJson,Toast.LENGTH_LONG).show();

                    }

                    if(code_id == 200) {

                        JSONObject json = new JSONObject(jsonResponse.toString());
                        JSONArray jsonarray = json.getJSONArray("msg");

                        for (int i = 0; i < jsonarray.length(); i++) {

                            JSONObject json1 = jsonarray.getJSONObject(i);

                            CardDetailModel cardDetailModel = new CardDetailModel();
                            cardDetailModel.setCard_name(json1.optString("brand"));
                            cardDetailModel.setCard_number(json1.optString("last4"));

                            JSONObject payment_id_JsonObject = json1.getJSONObject("PaymentMethod");
                            cardDetailModel.setPayment_id(payment_id_JsonObject.optString("id"));

                            cardDetailModelArrayList.add(cardDetailModel);

                        }

                        if(cardDetailModelArrayList!=null) {
                            TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                            transparent_layer.setVisibility(View.GONE);
                            progressDialog.setVisibility(View.GONE);
                            recyclerViewadapter = new CreditCardDetailAdapter(cardDetailModelArrayList, getActivity());
                            card_recycler_view.setAdapter(recyclerViewadapter);
                            recyclerViewadapter.notifyDataSetChanged();

                            recyclerViewadapter.setOnItemClickListner(new CreditCardDetailAdapter.OnItemClickListner() {
                                @Override
                                public void OnItemClicked(View view, int position) {
                                    SharedPreferences.Editor editor = sharedPreferences.edit();
                                    editor.putString(PreferenceClass.CREDIT_CARD_ARRAY,cardDetailModelArrayList.get(position).getCard_number());
                                    editor.putString(PreferenceClass.CREDIT_CARD_BRAND,cardDetailModelArrayList.get(position).getCard_name());
                                    editor.putString(PreferenceClass.PAYMENT_ID,cardDetailModelArrayList.get(position).getPayment_id());
                                    editor.commit();
                                    if(DealOrderFragment.DEAL_PAYMENT_METHOD){
                                        DealOrderFragment userAccountFragment = new DealOrderFragment();
                                        FragmentTransaction transaction = getFragmentManager().beginTransaction();
                                        transaction.replace(R.id.add_payment_main_container, userAccountFragment);
                                        transaction.addToBackStack(null);
                                        transaction.commit();
                                        DealOrderFragment.DEAL_PAYMENT_METHOD=false;
                                        DealsDetailFragment.FLAG_DEALS_DETAIL_FRAGMENT=true;
                                        FLAG_PAYMENT_METHOD = false;

                                    }
                                    else if(CartFragment.CART_PAYMENT_METHOD){
                                        CartFragment userAccountFragment = new CartFragment();
                                        FragmentTransaction transaction = getFragmentManager().beginTransaction();
                                        transaction.replace(R.id.add_payment_main_container, userAccountFragment);
                                        transaction.addToBackStack(null);
                                        transaction.commit();
                                        CartFragment.CART_PAYMENT_METHOD = false;
                                        FLAG_ADD_PAYMENT =true;
                                        AddressListFragment.FLAG_ADDRESS_LIST = false;
                                        CART_NOT_LOAD = true;

                                        FLAG_CASH_ON_DELIVERY = false;
                                        FLAG_PAYMENT_METHOD = true;
                                    }

                                }
                            });

                        }

                    }

                    else {
                        pbHeaderProgress.setVisibility(View.GONE);
                    }


                    }catch (JSONException e){

                    e.getCause();
                    Log.d("JSON",e.getMessage().toString());
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                pbHeaderProgress.setVisibility(View.GONE);
                //  ed_progress.setVisibility(View.GONE);
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

        payJsonObjectRequest.setRetryPolicy(new DefaultRetryPolicy(
                35000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        paymentRequestQueue.add(payJsonObjectRequest);


    }

}
