package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;


import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.location.Address;
import android.location.Geocoder;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
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
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.Functions;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.GoogleMapWork.MapsActivity;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.TabLayoutUtils;
import com.gmail.samehadar.iosdialog.CamomileSpinner;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;

/**
 * Created by Nabeel on 1/3/2018.
 */

public class AddressDetailFragment extends Fragment implements GoogleApiClient.OnConnectionFailedListener {

    Button cancle_add_address_btn,save_address_btn;
    ImageView back_icon;
    CamomileSpinner pbHeaderProgress;

    RelativeLayout transparent_layer,progressDialog;
    SharedPreferences sharedPreferences;
    EditText st_address,add_city,add_instructions;
    String add_state;
    String latitude,longitude;
    private int PLACE_PICKER_REQUEST = 2;

    RelativeLayout add_loc_div;
    public static TextView add_loc_tv;

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.add_address_detail, container, false);
        sharedPreferences = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);

        initUI(v);

       add_city.setFocusable(false);

        return v;
    }


    public void initUI(View v){
        progressDialog = v.findViewById(R.id.progressDialog);
        transparent_layer = v.findViewById(R.id.transparent_layer);
        String country_name = sharedPreferences.getString(PreferenceClass.COUNTRY_NAME,"");
        //All EditTex
        st_address = v.findViewById(R.id.st_address);
        add_city = v.findViewById(R.id.add_city);
      //  add_state = v.findViewById(R.id.add_state);
    //   add_zip = v.findViewById(R.id.add_zip);
      //  add_country = v.findViewById(R.id.add_country);
        add_instructions = v.findViewById(R.id.add_instructions);
        add_loc_div = v.findViewById(R.id.add_loc_div);
      //  country_div = v.findViewById(R.id.country_div);

        /// End
        add_loc_tv  = v.findViewById(R.id.add_loc_tv);

        if(SearchFragment.FLAG_COUNTRY_NAME) {
          //  add_country.setText(country_name);
            st_address.setText(sharedPreferences.getString(PreferenceClass.STREET,""));


            add_state = sharedPreferences.getString(PreferenceClass.STATE,"");
          //  add_state.setText(sharedPreferences.getString(PreferenceClass.STATE,""));
         //   add_zip.setText(sharedPreferences.getString(PreferenceClass.ZIP,""));
            add_instructions.setText(sharedPreferences.getString(PreferenceClass.INSTRUCTIONS,""));
            SearchFragment.FLAG_COUNTRY_NAME=false;
        }
        else {
          //  add_country.setText("Country");
        }

        cancle_add_address_btn = v.findViewById(R.id.cancle_add_address_btn);
        back_icon = v.findViewById(R.id.back_icon);
        save_address_btn = v.findViewById(R.id.save_address_btn);
        pbHeaderProgress = v.findViewById(R.id.pbHeaderProgress);
        pbHeaderProgress.start();

        save_address_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                saveAddressRequest();
            }
        });

        if(AddressListFragment.FLAG_ADDRESS_LIST){
            cancle_add_address_btn.setVisibility(View.GONE);
            back_icon.setVisibility(View.VISIBLE);
            AddressListFragment.FLAG_ADDRESS_LIST = false;
            UserAccountFragment.FLAG_DELIVER_ADDRESS = true;

            back_icon.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {


                    Functions.Hide_keyboard(getActivity());


                    Fragment restaurantMenuItemsFragment = new AddressListFragment();
                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                    transaction.add(R.id.address_detail_container, restaurantMenuItemsFragment,"parent").commit();
                   /* if (mGoogleApiClient2 != null && mGoogleApiClient2.isConnected()) {
                        mGoogleApiClient2.stopAutoManage((FragmentActivity) getContext());
                        mGoogleApiClient2.disconnect();
                    }*/
                }
            });

            add_loc_div.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                 /*   PlacePicker.IntentBuilder builder = new PlacePicker.IntentBuilder();
                    try {
                        startActivityForResult(builder.build(getActivity()), PLACE_PICKER_REQUEST);
                    } catch (GooglePlayServicesRepairableException | GooglePlayServicesNotAvailableException e) {
                        e.printStackTrace();
                    }*/

                 startActivity(new Intent(getContext(),MapsActivity.class));
                }
            });

        }

    }

    public void saveAddressRequest(){
        latitude = sharedPreferences.getString(PreferenceClass.LATITUDE, "");
        longitude = sharedPreferences.getString(PreferenceClass.LONGITUDE, "");
        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);
        //  cardetailArraylist = new ArrayList<>();
        String user_id = sharedPreferences.getString(PreferenceClass.pre_user_id,"");


        //Creating a string request
        RequestQueue queue = Volley.newRequestQueue(getContext());

        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("user_id", user_id);
            jsonObject.put("default","1");
            jsonObject.put("street",st_address.getText().toString());
            jsonObject.put("apartment","4ho");
            jsonObject.put("city",add_city.getText().toString());
            jsonObject.put("state","state");
            jsonObject.put("country","0");
            jsonObject.put("zip","0");
            jsonObject.put("instruction",add_instructions.getText().toString());
            jsonObject.put("lat",""+latitude);
            jsonObject.put("long",""+longitude);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest jsonObjReq = new JsonObjectRequest(Request.Method.POST, Config.ADD_DELIVERY_ADDRESS, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                String strJson =  response.toString();
                JSONObject jsonResponse = null;
                try {
                    jsonResponse = new JSONObject(strJson);

                    Log.d("JSONPost", jsonResponse.toString());

                    int code_id  = Integer.parseInt(jsonResponse.optString("code"));

                    TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                    transparent_layer.setVisibility(View.GONE);
                    progressDialog.setVisibility(View.GONE);

                    if(code_id == 200) {

                      //  Toast.makeText(getContext(),"Data Added Successfully",Toast.LENGTH_LONG).show();
                        Fragment restaurantMenuItemsFragment = new AddressListFragment();
                        FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                        transaction.add(R.id.address_detail_container, restaurantMenuItemsFragment,"parent").commit();

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

        jsonObjReq.setRetryPolicy(new DefaultRetryPolicy(
                35000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        queue.add(jsonObjReq);



    }

    @Override
    public void onStart() {
        super.onStart();
       // mGoogleApiClient2.connect();
    }

    @Override
    public void onStop() {
        super.onStop();
      //  mGoogleApiClient2.connect();
    }

    @Override
    public void onPause() {
        super.onPause();
       /* if (mGoogleApiClient2 != null && mGoogleApiClient2.isConnected()) {
            mGoogleApiClient2.stopAutoManage((FragmentActivity) getContext());
            mGoogleApiClient2.disconnect();
        }*/
    }

    @Override
    public void onResume() {
        super.onResume();
       /* if (mGoogleApiClient2 != null && mGoogleApiClient2.isConnected()) {
            mGoogleApiClient2.stopAutoManage((FragmentActivity) getContext());
            mGoogleApiClient2.disconnect();
        }*/

        if(MapsActivity.SAVE_LOCATION_ADDRESS) {
            MapsActivity.SAVE_LOCATION_ADDRESS = false;
            latitude = sharedPreferences.getString(PreferenceClass.LATITUDE, "");
            longitude = sharedPreferences.getString(PreferenceClass.LONGITUDE, "");

            Address locationAddress;

            locationAddress = getAddress(Double.parseDouble(latitude), Double.parseDouble(longitude));
            if (locationAddress != null) {

                String city = locationAddress.getLocality();

                String country = locationAddress.getCountryName();

                String address = city + " " + country;

                add_loc_tv.setText(latitude+","+longitude);



                add_city.setText(city);

                SharedPreferences.Editor editor = sharedPreferences.edit();
                editor.putString(PreferenceClass.LATITUDE,latitude);
                editor.putString(PreferenceClass.LONGITUDE,longitude);
                editor.putString(PreferenceClass.CURRENT_LOCATION_ADDRESS, address).commit();

            }
        }

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
    public void onDetach() {
        super.onDetach();
      //  mGoogleApiClient2 = null;
    }


    @Override
    public void onConnectionFailed(@NonNull ConnectionResult connectionResult) {
        Toast.makeText(getContext(),"On Failed",Toast.LENGTH_SHORT).show();
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {

      /*  if (requestCode == PLACE_PICKER_REQUEST) {
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
                SharedPreferences.Editor editor = sharedPreferences.edit();
                editor.putString(PreferenceClass.CURRENT_LOCATION_ADDRESS,latitude+","+ longitude).commit();
                add_loc_tv.setText(latitude+","+longitude);
                //  JSON_DATA_WEB_CALL();

            }
        }*/
    }
}
