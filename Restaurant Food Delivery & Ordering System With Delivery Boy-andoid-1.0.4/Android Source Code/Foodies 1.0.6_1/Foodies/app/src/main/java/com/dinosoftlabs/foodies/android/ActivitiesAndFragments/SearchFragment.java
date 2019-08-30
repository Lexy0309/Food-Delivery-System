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
import android.support.v7.widget.SearchView;
import android.text.Html;
import android.util.Log;
import android.util.TypedValue;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
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
import com.dinosoftlabs.foodies.android.Adapters.CountryListAdapter;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.Models.CountryListModel;
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

public class SearchFragment extends Fragment{
    SearchView searchView;
    ImageView close_country;
    ArrayList<CountryListModel> arrayListCountry;
    RecyclerView.LayoutManager recyclerViewlayoutManager;
    CountryListAdapter recyclerViewadapter;
    RecyclerView card_recycler_view;

    CamomileSpinner pbHeaderProgress;
    SharedPreferences sharedPreferences;
    public static boolean FLAG_COUNTRY_NAME;
    RelativeLayout transparent_layer,progressDialog;
    @SuppressWarnings("deprecation")
    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.search_screen, container, false);
        progressDialog = v.findViewById(R.id.progressDialog);
        transparent_layer = v.findViewById(R.id.transparent_layer);
        searchView = v.findViewById(R.id.simpleSearchView);
        searchView.setQueryHint(Html.fromHtml("<font color = #dddddd>" + "Search Country" + "</font>"));
        TextView searchText = (TextView)
                v.findViewById(android.support.v7.appcompat.R.id.search_src_text);
        searchText.setTextSize(TypedValue.COMPLEX_UNIT_SP,14);
        searchText.setPadding(0,0,0,0);
        LinearLayout searchEditFrame = (LinearLayout) searchView.findViewById(R.id.search_edit_frame); // Get the Linear Layout
// Get the associated LayoutParams and set leftMargin
        ((LinearLayout.LayoutParams) searchEditFrame.getLayoutParams()).leftMargin = 5;
        search(searchView);
        close_country = v.findViewById(R.id.close_country);
        card_recycler_view = v.findViewById(R.id.countries_list);
        recyclerViewlayoutManager = new LinearLayoutManager(getContext());
        card_recycler_view.setLayoutManager(recyclerViewlayoutManager);
        sharedPreferences = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);


        pbHeaderProgress = v.findViewById(R.id.pbHeaderProgress);
        pbHeaderProgress.start();

        close_country.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                AddressListFragment.FLAG_ADDRESS_LIST = true;
                Fragment restaurantMenuItemsFragment = new AddressDetailFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.search_main_container, restaurantMenuItemsFragment,"parent").commit();
             /*   if (mGoogleApiClient2 != null && mGoogleApiClient2.isConnected()) {
                    mGoogleApiClient2.stopAutoManage((FragmentActivity) getContext());
                    mGoogleApiClient2.disconnect();
                }*/
            }
        });

     //   sharedPreferences = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);

        getCountryList();




        return v;
    }



    public void getCountryList(){
        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);

        arrayListCountry = new ArrayList<>();
        RequestQueue paymentRequestQueue = Volley.newRequestQueue(getContext());


        JsonObjectRequest payJsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.SHOW_COUNTRIES_LIST, null, new Response.Listener<JSONObject>() {
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
                        JSONArray jsonarray = json.getJSONArray("countries");

                        for (int i = 0; i < jsonarray.length(); i++) {

                            JSONObject json1 = jsonarray.getJSONObject(i);

                            JSONObject jsonObjTax = json1.getJSONObject("Tax");

                            CountryListModel cardDetailModel = new CountryListModel();
                            cardDetailModel.setCountry_name(jsonObjTax.optString("country"));
                            if(jsonObjTax.optString("country_code").isEmpty())
                            {
                                cardDetailModel.setCountry_code("");
                            }
                            else {
                                cardDetailModel.setCountry_code(jsonObjTax.optString("country_code"));
                            }

                            arrayListCountry.add(cardDetailModel);

                        }
                        if(arrayListCountry!=null) {
                            TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                            transparent_layer.setVisibility(View.GONE);
                            progressDialog.setVisibility(View.GONE);
                            recyclerViewadapter = new CountryListAdapter(arrayListCountry, getActivity());
                            card_recycler_view.setAdapter(recyclerViewadapter);
                            recyclerViewadapter.notifyDataSetChanged();

                            recyclerViewadapter.setOnItemClickListner(new CountryListAdapter.OnItemClickListner() {
                                @Override
                                public void OnItemClicked(View view, int position) {

                                    SharedPreferences.Editor editor = sharedPreferences.edit();
                                    editor.putString(PreferenceClass.COUNTRY_NAME,arrayListCountry.get(position).getCountry_name());
                                    editor.commit();
                                    FLAG_COUNTRY_NAME = true;
                                 //   Toast.makeText(getContext(),arrayListCountry.get(position).getCountry_name(),Toast.LENGTH_SHORT).show();
                                    AddressListFragment.FLAG_ADDRESS_LIST = true;
                                    Fragment restaurantMenuItemsFragment = new AddressDetailFragment();
                                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                                    transaction.add(R.id.search_main_container, restaurantMenuItemsFragment,"parent").commit();
                                  /*  if (mGoogleApiClient2 != null && mGoogleApiClient2.isConnected()) {
                                        mGoogleApiClient2.stopAutoManage((FragmentActivity) getContext());
                                        mGoogleApiClient2.disconnect();
                                    }
*/
                                }
                            });
                        }






                    }


                }catch (JSONException e){

                    e.getCause();
                    Log.d("JSON",e.getMessage().toString());
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                //  ed_progress.setVisibility(View.GONE);
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);
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

    private void search(android.support.v7.widget.SearchView searchView) {

        searchView.setOnQueryTextListener(new android.support.v7.widget.SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {

                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {

                if (recyclerViewadapter != null) recyclerViewadapter.getFilter().filter(newText);
                return true;
            }
        });
    }



}




    /*@Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.search_bar, menu);

        MenuItem item = menu.findItem(R.id.action_search);
        searchView.setMenuItem(item);

        return true;
    }*/

