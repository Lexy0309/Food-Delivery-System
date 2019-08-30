package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.content.Context;
import android.content.SharedPreferences;
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
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.gmail.samehadar.iosdialog.CamomileSpinner;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.TabLayoutUtils;


import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by Nabeel on 12/30/2017.
 */

public class EditAccountFragment extends Fragment {

    ImageView back_icon;
    EditText first_name,last_name,phone_number,ed_email;
    Button btn_edit_done;
    String first_name_str,last_name_str,user_id,email,phone;
    SharedPreferences sharedPreferences;

    CamomileSpinner editAccountProgress;
    RelativeLayout transparent_layer,progressDialog;
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);


    }
    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.edit_account_fragment,container,false);
        sharedPreferences = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);
        init(v);
        return v;
    }

    public void init(View v){
        editAccountProgress = v.findViewById(R.id.editAccountProgress);
        editAccountProgress.start();
        progressDialog = v.findViewById(R.id.progressDialog);
        transparent_layer = v.findViewById(R.id.transparent_layer);
        first_name = v.findViewById(R.id.first_name);
        last_name = v.findViewById(R.id.last_name);
        phone_number = v.findViewById(R.id.ed_phone_number);
        ed_email = v.findViewById(R.id.ed_edit_email);

        btn_edit_done = v.findViewById(R.id.btn_edit_done);
        btn_edit_done.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                editUserProfile();
            }
        });

        first_name_str = sharedPreferences.getString(PreferenceClass.pre_first,"");
        last_name_str = sharedPreferences.getString(PreferenceClass.pre_last,"");
        user_id = sharedPreferences.getString(PreferenceClass.pre_user_id,"");
        email = sharedPreferences.getString(PreferenceClass.pre_email,"");
        phone = sharedPreferences.getString(PreferenceClass.pre_contact,"");


        first_name.setText(first_name_str);
        last_name.setText(last_name_str);
        ed_email.setText(email);
        phone_number.setText(phone);


        back_icon = v.findViewById(R.id.back_icon);
        back_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                UserAccountFragment userAccountFragment = new UserAccountFragment();
                FragmentTransaction transaction = getFragmentManager().beginTransaction();
                transaction.replace(R.id.edit_account_main_container, userAccountFragment);
                transaction.addToBackStack(null);
                transaction.commit();

            }
        });

    }


    public void editUserProfile(){
        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);

        RequestQueue queue = Volley.newRequestQueue(getContext());
        JSONObject editJsonObject = new JSONObject();

        try {

            editJsonObject.put("user_id",user_id);
            if(first_name.getText().toString().isEmpty()){
                editJsonObject.put("first_name",first_name_str);
            }
            else {
                editJsonObject.put("first_name", first_name.getText().toString());
            }
            if(last_name.getText().toString().isEmpty()) {
                editJsonObject.put("last_name", last_name_str);
            }
            else {
                editJsonObject.put("last_name", last_name.getText().toString());
            }
            editJsonObject.put("email",email);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest editJsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.EDIT_PROFILE, editJsonObject, new Response.Listener<JSONObject>() {
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
                        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                        transparent_layer.setVisibility(View.GONE);
                        progressDialog.setVisibility(View.GONE);
                        JSONObject json = new JSONObject(jsonResponse.toString());
                        JSONObject resultObj = json.getJSONObject("msg");
                        JSONObject json1 = new JSONObject(resultObj.toString());
                        JSONObject resultObj1 = json1.getJSONObject("UserInfo");

                        Toast.makeText(getContext(),"Profile updated successfully",Toast.LENGTH_LONG).show();
                        SharedPreferences.Editor editor = sharedPreferences.edit();
                        editor.putString(PreferenceClass.pre_first,resultObj1.optString("first_name"));
                        editor.putString(PreferenceClass.pre_last,resultObj1.optString("last_name"));
                        editor.commit();

                        UserAccountFragment userAccountFragment = new UserAccountFragment();
                        FragmentTransaction transaction = getFragmentManager().beginTransaction();
                        transaction.replace(R.id.edit_account_main_container, userAccountFragment);
                        transaction.addToBackStack(null);
                        transaction.commit();


                    }


                    }catch (JSONException e){
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

        queue.add(editJsonObjectRequest);

    }


}
