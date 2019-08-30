package com.dinosoftlabs.foodies.android.RActivitiesAndFragments;

import android.app.DatePickerDialog;
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
import android.widget.DatePicker;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;

import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RAdapters.RCurrentJobAdapter;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RAdapters.RPendingJobAdapter;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RiderModels.RiderJobModel;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.Locale;
import java.util.Map;
import java.util.StringTokenizer;

/**
 * Created by Nabeel on 1/19/2018.
 */

public class RTodayJobFragment extends Fragment {

    ImageView back_icon,filter_icon;
    private Calendar myCalendar;
    String dateText;
    ArrayList<RiderJobModel> riderCurrentJobList;
    SharedPreferences sPref;
    RCurrentJobAdapter dataAdapter2;
    RecyclerView current_job_rv;
    RecyclerView.LayoutManager recyclerViewlayoutManager;
    ProgressBar pb_today_jobs;
    public static boolean FLAG_TODAY_JOB;
    RelativeLayout no_job_div;


    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.rider_todays_job, container, false);
        sPref = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);

        FrameLayout frameLayout = v.findViewById(R.id.show_jobs_container);
        FontHelper.applyFont(getContext(),frameLayout, AllConstants.verdana);

        myCalendar = Calendar.getInstance();
        init(v);
        SimpleDateFormat timeStampFormat = new SimpleDateFormat("yyyy-MM-dd");
       Date myDate = new Date();
        String date = timeStampFormat.format(myDate);
        showRiderOrderList(date);
        return v;
    }

    public void init(View v){

        no_job_div = v.findViewById(R.id.no_job_div);
        back_icon = v.findViewById(R.id.back_icon);
        filter_icon=v.findViewById(R.id.filter_img);
        datePickerDialog();
        pb_today_jobs = v.findViewById(R.id.pb_today_jobs);

        current_job_rv = v.findViewById(R.id.job_list_base_onDate);
        current_job_rv.setHasFixedSize(true);
        recyclerViewlayoutManager = new LinearLayoutManager(getContext());
        current_job_rv.setLayoutManager(recyclerViewlayoutManager);

        back_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                RProfileFragment rJobsFragment = new RProfileFragment();
                FragmentTransaction transaction = getFragmentManager().beginTransaction();
                transaction.replace(R.id.show_jobs_container, rJobsFragment);
                transaction.addToBackStack(null);
                transaction.commit();
            }
        });

    }


    private void datePickerDialog(){


        final DatePickerDialog.OnDateSetListener date = new DatePickerDialog.OnDateSetListener() {

            @Override
            public void onDateSet(DatePicker view, int year, int monthOfYear,
                                  int dayOfMonth) {
                // TODO Auto-generated method stub
                myCalendar.set(Calendar.YEAR, year);
                myCalendar.set(Calendar.MONTH, monthOfYear);
                myCalendar.set(Calendar.DAY_OF_MONTH, dayOfMonth);
                updateLabel();
            }

        };


        filter_icon.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                // TODO Auto-generated method stub
                new DatePickerDialog(getContext(), date, myCalendar
                        .get(Calendar.YEAR), myCalendar.get(Calendar.MONTH),
                        myCalendar.get(Calendar.DAY_OF_MONTH)).show();
            }
        });

    }

    private void updateLabel() {
        String myFormat = "yyyy-MM-dd"; //In which you need put here
        SimpleDateFormat sdf = new SimpleDateFormat(myFormat, Locale.US);
        pb_today_jobs.setVisibility(View.VISIBLE);
        dateText = sdf.format(myCalendar.getTime());
        showRiderOrderList(dateText);
    }


    public void showRiderOrderList(String date){
        riderCurrentJobList = new ArrayList<>();
        RequestQueue queue = Volley.newRequestQueue(getContext());

        String user_id = sPref.getString(PreferenceClass.pre_user_id,"");
        JSONObject jsonObject = new JSONObject();

        try {
            jsonObject.put("user_id",user_id);
            jsonObject.put("date", date);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.SHOW_RIDER_ORDER_BASE_ONDATE, jsonObject, new Response.Listener<JSONObject>() {
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

                        for (int i = 0; i < jsonarray.length(); i++) {

                            JSONObject json1 = jsonarray.getJSONObject(i);
                            JSONObject orderObj = json1.getJSONObject("Order");
                            JSONObject riderObj = json1.getJSONObject("RiderOrder");
                            JSONObject restaurantObj = orderObj.getJSONObject("Restaurant");
                            JSONObject currencyObj = restaurantObj.getJSONObject("Currency");
                            JSONObject restaurantLocObj = restaurantObj.getJSONObject("RestaurantLocation");

                            JSONObject userInfoObj = orderObj.getJSONObject("UserInfo");
                            JSONObject userAddressObj = orderObj.getJSONObject("Address");
                            String symbol = currencyObj.optString("symbol");
                            //  JSONObject jsonObjTax = json1.getJSONObject("Tax");

                            RiderJobModel riderJobModel = new RiderJobModel();

                            //All User Info

                            riderJobModel.setUser_f_name(userInfoObj.optString("first_name"));
                            riderJobModel.setUser_l_name(userInfoObj.optString("last_name"));
                            riderJobModel.setUser_phone_number(userInfoObj.optString("phone"));
                            riderJobModel.setUser_street(userAddressObj.optString("street"));
                            riderJobModel.setUser_apartment(userAddressObj.optString("apartment"));
                            riderJobModel.setUser_city(userAddressObj.optString("city"));
                            riderJobModel.setUser_state(userAddressObj.optString("state"));
                            riderJobModel.setUser_lat(userAddressObj.optString("lat"));
                            riderJobModel.setUser_long(userAddressObj.optString("long"));

                            /// All Hotel Detail Data

                            riderJobModel.setHotel_lat(restaurantLocObj.optString("lat"));
                            riderJobModel.setHotel_long(restaurantLocObj.optString("long"));

                            riderJobModel.setOrder_number(riderObj.optString("order_id"));
                            riderJobModel.setHotel_name(restaurantObj.optString("name"));
                            riderJobModel.setHotel_phone_number(restaurantObj.optString("phone"));
                            riderJobModel.setOrder_tax(orderObj.optString("tax"));
                            riderJobModel.setOrder_delivery_fee(restaurantObj.optString("delivery_fee"));


                            riderJobModel.setHotel_zip(restaurantLocObj.optString("zip"));
                            riderJobModel.setHotel_city(restaurantLocObj.optString("city"));
                            riderJobModel.setHotel_state(restaurantLocObj.optString("state"));
                            riderJobModel.setHotel_country(restaurantLocObj.optString("country"));
                            String zip = restaurantLocObj.optString("zip");
                            String city = restaurantLocObj.optString("city");
                            String state = restaurantLocObj.optString("state");
                            String country = restaurantLocObj.optString("country");

                            riderJobModel.setHotel_address(zip+", "+city+", "+state+", "+country);
                            String cash_status = orderObj.optString("cod");

                            if(cash_status.equalsIgnoreCase("0")) {
                                riderJobModel.setOrder_cash_status("Credit Card");
                            }
                            else {
                                riderJobModel.setOrder_cash_status("Cash on Delivery");
                            }


                            riderJobModel.setRider_symbol(currencyObj.optString("symbol"));
                            riderJobModel.setOrder_price(orderObj.optString("price"));

                            /// Set Time Formate

                            String date_time = orderObj.optString("created");
                            //  String date = date_time.substring(0,10);
                            //  String time = date_time.substring(11,19);

                            StringTokenizer tk = new StringTokenizer(date_time);
                            String date = tk.nextToken();
                            String time = tk.nextToken();

                            SimpleDateFormat sdf = new SimpleDateFormat("hh:mm:ss");
                            SimpleDateFormat sdfs = new SimpleDateFormat("hh:mm a");
                            Date dt;
                            try {
                                dt = sdf.parse(time);
                                System.out.println("Time Display: " + sdfs.format(dt));
                                String finalTime = sdfs.format(dt);
                                riderJobModel.setOrder_time(finalTime);
                                // <-- I got result here
                            } catch (ParseException e) {
                                // TODO Auto-generated catch block
                                e.printStackTrace();
                            }


                            riderCurrentJobList.add(riderJobModel);

                        }
                        if(riderCurrentJobList!=null) {

                            if(riderCurrentJobList.size()>0){
                                no_job_div.setVisibility(View.GONE);
                            }

                            pb_today_jobs.setVisibility(View.GONE);
                            dataAdapter2 = new RCurrentJobAdapter(riderCurrentJobList, getActivity());
                            current_job_rv.setAdapter(dataAdapter2);
                            dataAdapter2.notifyDataSetChanged();

                            dataAdapter2.setOnItemClickListner(new RPendingJobAdapter.OnItemClickListner() {
                                @Override
                                public void OnItemClicked(View view, int position) {


                                    SharedPreferences.Editor editor = sPref.edit();

                                    editor.putString(PreferenceClass.RIDER_ORDER_NUMBER,riderCurrentJobList.get(position).getOrder_number());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_NAME,riderCurrentJobList.get(position).getHotel_name());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_ZIP,riderCurrentJobList.get(position).getHotel_zip());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_CITY,riderCurrentJobList.get(position).getHotel_city());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_STATE,riderCurrentJobList.get(position).getHotel_state());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_COUNTRY,riderCurrentJobList.get(position).getHotel_country());

                                    editor.putString(PreferenceClass.RIDER_PAYMENT_STATUS,riderCurrentJobList.get(position).getOrder_cash_status());
                                    editor.putString(PreferenceClass.RIDER_TOTAL_PAYMENT,riderCurrentJobList.get(position).getOrder_price());
                                    editor.putString(PreferenceClass.RIDER_TIME,riderCurrentJobList.get(position).getOrder_time());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_PHONE,riderCurrentJobList.get(position).getHotel_phone_number());
                                    editor.putString(PreferenceClass.RIDER_ORDER_SYMBOL,riderCurrentJobList.get(position).getRider_symbol());
                                    editor.putString(PreferenceClass.RIDER_USER_F_NAME,riderCurrentJobList.get(position).getUser_f_name());
                                    editor.putString(PreferenceClass.RIDER_USER_L_NAME,riderCurrentJobList.get(position).getUser_l_name());
                                    editor.putString(PreferenceClass.RIDER_USER_PHONE,riderCurrentJobList.get(position).getUser_phone_number());
                                    editor.putString(PreferenceClass.RIDER_USER_STREET,riderCurrentJobList.get(position).getUser_street());
                                    editor.putString(PreferenceClass.RIDER_USER_APARTMENT,riderCurrentJobList.get(position).getUser_apartment());
                                    editor.putString(PreferenceClass.RIDER_USER_CITY,riderCurrentJobList.get(position).getUser_city());
                                    editor.putString(PreferenceClass.RIDER_USER_STATE,riderCurrentJobList.get(position).getUser_state());
                                    editor.putString(PreferenceClass.RIDER_ORDER_TAX,riderCurrentJobList.get(position).getOrder_tax());
                                    editor.putString(PreferenceClass.RIDER_ORDER_DELIVER_FEE,riderCurrentJobList.get(position).getOrder_delivery_fee());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_LAT,riderCurrentJobList.get(position).getHotel_lat());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_LONG,riderCurrentJobList.get(position).getHotel_long());
                                    editor.putString(PreferenceClass.RIDER_USER_LAT,riderCurrentJobList.get(position).getUser_lat());
                                    editor.putString(PreferenceClass.RIDER_USER_LONG,riderCurrentJobList.get(position).getUser_long());

                                    editor.commit();

                                    FLAG_TODAY_JOB = true;
                                    Fragment restaurantMenuItemsFragment = new ROrderDetailFragment();
                                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                                    transaction.add(R.id.show_jobs_container, restaurantMenuItemsFragment,"parent").commit();

                                }
                            });
                        }


                    }


                    }
                    catch (JSONException e){
                    e.getStackTrace();
                    }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

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

        queue.add(jsonObjectRequest);

    }

}
