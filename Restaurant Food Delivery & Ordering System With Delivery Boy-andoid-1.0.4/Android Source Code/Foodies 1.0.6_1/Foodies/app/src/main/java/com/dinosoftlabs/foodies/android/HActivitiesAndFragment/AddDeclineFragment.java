package com.dinosoftlabs.foodies.android.HActivitiesAndFragment;


import android.content.Context;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.OrderDetailFragment;
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

/**
 * Created by Nabeel on 2/15/2018.
 */

public class AddDeclineFragment extends Fragment {

    Button btn_done;
    TextView accept_decline_title_tv;
    ImageView back_icon;
    SharedPreferences sPref;
    String order_id;
    EditText reson_txt;
    ProgressBar progressBar;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.hotel_add_decline_status, container, false);
       sPref = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);

        FrameLayout frameLayout = view.findViewById(R.id.add_decline_main);
        FontHelper.applyFont(getContext(), frameLayout, AllConstants.verdana);
        order_id = sPref.getString(PreferenceClass.ORDER_ID,"");
        frameLayout.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                return true;
            }
        });

        initUI(view);

        return view;
    }

    public void initUI(View v){
        progressBar = v.findViewById(R.id.pb_acc_dec);
        btn_done = v.findViewById(R.id.btn_done);
        back_icon = v.findViewById(R.id.back_icon);
        accept_decline_title_tv = v.findViewById(R.id.accept_decline_title_tv);
        reson_txt = v.findViewById(R.id.ed_message);
        String order_title = sPref.getString(PreferenceClass.ORDER_HEADER,"");

        accept_decline_title_tv.setText(order_title);
        if(OrderDetailFragment.FLAG_ACCEPT){
            reson_txt.setHint("Type rider instructions (Optional)");
        }
        else {
            reson_txt.setHint("Type rider instructions");
        }

        back_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new OrderDetailFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.add_decline_main, restaurantMenuItemsFragment,"parent").commit();
                OrderDetailFragment.FLAG_ACCEPT = false;
            }
        });

        btn_done.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                progressBar.setVisibility(View.VISIBLE);
                if (!OrderDetailFragment.FLAG_ACCEPT) {
                    OrderDetailFragment.FLAG_ACCEPT = false;
                    if(reson_txt.getText().toString().isEmpty()){
                        progressBar.setVisibility(View.GONE);
                        Toast.makeText(getContext(),"Type rider instructions",Toast.LENGTH_LONG).show();

                    }
                    else {
                        setAccDecStatus();
                    }
                }
                else {
                    setAccDecStatus();
                }

            }
        });

    }

    public void setAccDecStatus(){
        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MMM-dd hh:mm:ss");
        String currentDateandTime = sdf.format(new Date());
        RequestQueue requestQueue = Volley.newRequestQueue(getContext());

        JSONObject jsonObject = new JSONObject();

        try {
            jsonObject.put("order_id", order_id);
            jsonObject.put("time", currentDateandTime);
            if (OrderDetailFragment.FLAG_ACCEPT) {
                jsonObject.put("status", "1");
                jsonObject.put("rejected_reason", "");
                jsonObject.put("accepted_reason", reson_txt.getText().toString());
              //  OrderDetailFragment.FLAG_ACCEPT = false;

            }
            else {
                jsonObject.put("status", "2");
                jsonObject.put("rejected_reason", reson_txt.getText().toString());
                jsonObject.put("accepted_reason", "");

            }
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.ACCEPT_DECLINE_STATUS, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                String str = response.toString();

                try {
                    JSONObject jsonObject1 = new JSONObject(str);

                    int code = Integer.parseInt(jsonObject1.optString("code"));

                    if(code==200){

                        progressBar.setVisibility(View.GONE);
                        HJobsFragment userAccountFragment = new HJobsFragment();
                        FragmentTransaction transaction = getFragmentManager().beginTransaction();
                        transaction.replace(R.id.add_decline_main, userAccountFragment);
                        transaction.addToBackStack(null);
                        transaction.commit();

                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }

            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
              //  Toast.makeText(getContext(),error.toString(),Toast.LENGTH_SHORT).show();
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

        requestQueue.add(jsonObjectRequest);

    }

}
