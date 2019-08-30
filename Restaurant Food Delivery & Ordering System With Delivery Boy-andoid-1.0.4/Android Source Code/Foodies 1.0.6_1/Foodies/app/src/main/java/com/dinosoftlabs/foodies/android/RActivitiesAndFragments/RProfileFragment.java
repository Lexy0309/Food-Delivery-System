package com.dinosoftlabs.foodies.android.RActivitiesAndFragments;

import android.Manifest;
import android.content.Context;
import android.content.DialogInterface;
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
import android.support.v4.content.ContextCompat;
import android.support.v7.app.AlertDialog;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.FrameLayout;
import android.widget.RelativeLayout;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.gmail.samehadar.iosdialog.CamomileSpinner;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.ChangePasswordFragment;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.MainActivity;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.ReviewListFragment;
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.Services.UpdateLocation;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.HashMap;
import java.util.Map;


/**
 * Created by Nabeel on 1/15/2018.
 */

public class RProfileFragment extends Fragment{

    RelativeLayout profile_div,log_out_div,today_job_div,weekly_earning_div,schedule_div,change_password_div,app_help_div,review_div;
    SharedPreferences profile_pref;
    public static boolean FLAG_RIDER;
    String user_id;
    CamomileSpinner orderProgressBar;
    RelativeLayout transparent_layer,progressDialog;
    public static boolean RIDER_REVIEW;
    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.rider_profile_fragment, container, false);
        profile_pref = getContext().getSharedPreferences(PreferenceClass.user,Context.MODE_PRIVATE);
        user_id = profile_pref.getString(PreferenceClass.pre_user_id, "");
        FrameLayout frameLayout = v.findViewById(R.id.profile_main_container);
        FontHelper.applyFont(getContext(),frameLayout, AllConstants.verdana);
        init(v);

        return v;
    }

    public void init(View v){

        progressDialog = v.findViewById(R.id.progressDialog);
        transparent_layer = v.findViewById(R.id.transparent_layer);
        orderProgressBar = v.findViewById(R.id.orderProgress);
        orderProgressBar.start();
        profile_div = v.findViewById(R.id.profile_div);
        review_div = v.findViewById(R.id.review_div);
        log_out_div = v.findViewById(R.id.log_out_div);
        today_job_div = v.findViewById(R.id.today_job_div);
        weekly_earning_div = v.findViewById(R.id.weekly_earning_div);
        schedule_div = v.findViewById(R.id.schedule_div);
        change_password_div = v.findViewById(R.id.change_password_div);
        app_help_div = v.findViewById(R.id.app_help_div);
        today_job_div = v.findViewById(R.id.today_job_div);

        log_out_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                showUserOnlineStatus();

            }
        });

        profile_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                Fragment restaurantMenuItemsFragment = new RiderAccountInfoFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.profile_main_container, restaurantMenuItemsFragment,"parent").commit();

            }
        });

        weekly_earning_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                Fragment restaurantMenuItemsFragment = new RWeeklyEarningFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.profile_main_container, restaurantMenuItemsFragment,"parent").commit();
            }
        });


        app_help_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                Fragment restaurantMenuItemsFragment = new RiderAppHelpFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.profile_main_container, restaurantMenuItemsFragment,"parent").commit();
            }
        });

        change_password_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new ChangePasswordFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.profile_main_container, restaurantMenuItemsFragment,"ParentFragment").commit();
                FLAG_RIDER = true;
            }
        });

        schedule_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new RAvailablityFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.profile_main_container, restaurantMenuItemsFragment,"ParentFragment").commit();
            }
        });

        today_job_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new RTodayJobFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.profile_main_container, restaurantMenuItemsFragment,"ParentFragment").commit();
            }
        });

        review_div .setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                RIDER_REVIEW = true;
                Fragment reviewListFragment = new ReviewListFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.replace(R.id.profile_main_container, reviewListFragment,"ParentFragment_MenuItems").commit();
            }
        });

    }

    private void logOutUser(){
        SharedPreferences.Editor editor = profile_pref.edit();
        editor.putString(PreferenceClass.USER_TYPE,"");
        editor.putString(PreferenceClass.pre_email, "");
        editor.putString(PreferenceClass.pre_pass, "");
        editor.putString(PreferenceClass.pre_first, "");
        editor.putString(PreferenceClass.pre_last, "");
        editor.putString(PreferenceClass.pre_contact, "");
        editor.putString(PreferenceClass.pre_user_id, "");
        editor.putString(PreferenceClass.ADMIN_USER_ID,"");

        editor.putBoolean(PreferenceClass.IS_LOGIN, false);
        editor.commit();
        getActivity().startActivity(new Intent(getContext(), MainActivity.class));
        getActivity().finish();

        Intent intent = new Intent(getContext(), UpdateLocation.class);
        getContext().stopService(intent);

    }

    public void showUserOnlineStatus(){

        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);

        RequestQueue requestQueue = Volley.newRequestQueue(getContext());

        JSONObject jsonObject = new JSONObject();

        Calendar c = Calendar.getInstance();
        System.out.println("Current time => "+c.getTime());

        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        String formattedDate = df.format(c.getTime());

        try {
            jsonObject.put("user_id",user_id);
            jsonObject.put("datetime",formattedDate);

        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.SHOW_USER_ONLINE_STATUS, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                Log.d("JSONPost", response.toString());
                String strJson =  response.toString();
                JSONObject jsonResponse = null;

                try {
                    jsonResponse = new JSONObject(strJson);

                    int code_id  = Integer.parseInt(jsonResponse.optString("code"));

                    SharedPreferences.Editor editor = profile_pref.edit();

                 if(code_id == 200) {

                    String msg = jsonResponse.optString("msg");

                     if(msg.equalsIgnoreCase("1") ){

                         editor.putString(PreferenceClass.RIDER_ONLINE_STATUS,"0");
                         editor.commit();
                     }
                     else {

                         editor.putString(PreferenceClass.RIDER_ONLINE_STATUS,"1");
                         editor.commit();
                     }


                     String msgFinal = profile_pref.getString(PreferenceClass.RIDER_ONLINE_STATUS,"");

                    if(msgFinal.equalsIgnoreCase("1")){
                        logOutUser();
                    }
                    else {
                        showDialog();
                    }

                     transparent_layer.setVisibility(View.GONE);
                     progressDialog.setVisibility(View.GONE);



                 }

                 else {
                     transparent_layer.setVisibility(View.GONE);
                     progressDialog.setVisibility(View.GONE);
                     Log.e("Error", strJson);

                 }

                } catch (JSONException e) {
                    e.printStackTrace();
                    Log.d("JSONPost",e.toString());
                }


            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);
                Log.d("JSONPost",error.toString());

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

        jsonObjectRequest.setRetryPolicy(new DefaultRetryPolicy(
                35000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));

        requestQueue.add(jsonObjectRequest);


    }


    public void showDialog(){

        AlertDialog.Builder builder1 = new AlertDialog.Builder(getContext());
        builder1.setMessage("You must first call or chat with us.");
        builder1.setTitle("Select call or message!");

        builder1.setPositiveButton(
                "Call",
                new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        phoneCall();
                        dialog.cancel();
                    }
                });

        builder1.setNegativeButton(
                "Chat",
                new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        // do nothing
                        RiderMainActivity.CHAT_FLAG = true;
                        startActivity(new Intent(getContext(),RiderMainActivity.class));
                        getActivity().finish();
                        dialog.dismiss();
                    }
                });

        AlertDialog alert11 = builder1.create();
        alert11.show();

    }

    public void phoneCall() {

        AlertDialog.Builder builder1 = new AlertDialog.Builder(getContext());
        builder1.setMessage("You must first call or chat with us.");
        builder1.setTitle("Make a call!");
        builder1.setCancelable(true);

        builder1.setPositiveButton(
                "Call",
                new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {

                        onCall();

                        dialog.cancel();
                    }
                });

        builder1.setNegativeButton(
                "Cancle",
                new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        dialog.cancel();
                    }
                });

        AlertDialog alert11 = builder1.create();
        alert11.show();

    }

    public void onCall() {
        int permissionCheck = ContextCompat.checkSelfPermission(getContext(), Manifest.permission.CALL_PHONE);

        if (permissionCheck != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(
                    getActivity(),
                    new String[]{Manifest.permission.CALL_PHONE},
                    123);
        } else {
            startActivity(new Intent(Intent.ACTION_CALL).setData(Uri.parse("tel:"+getContext().getString(R.string.admin_contact_number))));
        }
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        switch (requestCode) {

            case 123:
                if ((grantResults.length > 0) && (grantResults[0] == PackageManager.PERMISSION_GRANTED)) {
                    onCall();
                } else {
                    Log.d("TAG", "Call Permission Not Granted");
                }
                break;

            default:
                break;
        }
    }

}
