package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;


import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.view.inputmethod.InputMethodManager;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.Spinner;
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
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.HActivitiesAndFragment.HotelMainActivity;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RiderMainActivity;
import com.dinosoftlabs.foodies.android.Utils.TabLayoutUtils;
import com.facebook.AccessToken;
import com.facebook.CallbackManager;
import com.facebook.FacebookCallback;
import com.facebook.FacebookException;
import com.facebook.FacebookSdk;
import com.facebook.GraphRequest;
import com.facebook.GraphResponse;
import com.facebook.appevents.AppEventsLogger;
import com.facebook.login.LoginManager;
import com.facebook.login.LoginResult;
import com.facebook.login.widget.LoginButton;
import com.github.reinaldoarrosi.maskededittext.MaskedEditText;
import com.gmail.samehadar.iosdialog.CamomileSpinner;
import com.google.android.gms.auth.api.Auth;
import com.google.android.gms.auth.api.signin.GoogleSignIn;
import com.google.android.gms.auth.api.signin.GoogleSignInAccount;
import com.google.android.gms.auth.api.signin.GoogleSignInClient;
import com.google.android.gms.auth.api.signin.GoogleSignInOptions;
import com.google.android.gms.auth.api.signin.GoogleSignInResult;
import com.google.firebase.iid.FirebaseInstanceId;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.Arrays;
import java.util.HashMap;
import java.util.Map;
import java.util.regex.Pattern;

import at.markushi.ui.CircleButton;

import static android.content.Context.INPUT_METHOD_SERVICE;


public class SingUpActivity extends Fragment implements AdapterView.OnItemSelectedListener {

    public static final Pattern EMAIL_ADDRESS_PATTERN = Pattern.compile(
            "[a-zA-Z0-9\\+\\.\\_\\%\\-\\+]{1,256}" +
                    "\\@" +
                    "[a-zA-Z0-9][a-zA-Z0-9\\-]{0,64}" +
                    "(" +
                    "\\." +
                    "[a-zA-Z0-9][a-zA-Z0-9\\-]{0,25}" +
                    ")+"
    );

    CircleButton confirm_btn;

    LinearLayout div2;

    ImageView back_sign_up, back_icon_verification, back_icon_confirm;

    LinearLayout verification_div, main_sign_up, confirm_div, verification_main_screen, confirmation_main_screen;
    Button btn_signup, btn_done, btn_google, btn_fb;
    RelativeLayout div;

    EditText editText1, editText2, editText3, editText4, e_first, e_last, e_email, e_password;
    MaskedEditText phone;
     TextView countryCode, btn_resend;

    RelativeLayout fb_div;

    FrameLayout main_sign_up_div;

    public boolean FLAG_CONFIRMATION_SCREEN, FLAG_VERIFICATION_SCREEN;
    private Spinner spinner;
    private static final String[] paths = {"Pakistan", "Canada"};

    SharedPreferences preferences;

    CamomileSpinner progressBar;
    RelativeLayout transparent_layer, progressDialog;
    GoogleSignInClient mGoogleSignInClient;
    TextView fb_btn;
    LoginButton login_button_fb;
    CallbackManager callbackManager;

    RelativeLayout google_sign_in_div;



    @SuppressLint("RestrictedApi")
    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        getActivity().getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_ADJUST_PAN | WindowManager.LayoutParams.SOFT_INPUT_MASK_ADJUST);
        View v = inflater.inflate(R.layout.activity_sing_up, container, false);


        progressBar = v.findViewById(R.id.signUpProgress);
        progressBar.start();
        progressDialog = v.findViewById(R.id.progressDialog);
        transparent_layer = v.findViewById(R.id.transparent_layer);

        preferences = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);

        // Google SignIn Initialize

        // Configure Google Sign In
        GoogleSignInOptions gso = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
                .requestIdToken(getString(R.string.default_web_client_id))
                .requestEmail()
                .build();
        mGoogleSignInClient = GoogleSignIn.getClient(getContext(), gso);

        // End//

        /// FB Login
        FacebookSdk.sdkInitialize(getContext());
        AppEventsLogger.activateApp(getContext());

        callbackManager = CallbackManager.Factory.create();

        /// End


        spinner = (Spinner) v.findViewById(R.id.spinner);




        back_sign_up = (ImageView) v.findViewById(R.id.back_icon);
        countryCode = (TextView) v.findViewById(R.id.country_code);
        phone = (MaskedEditText) v.findViewById(R.id.country_phone);
        verification_div = v.findViewById(R.id.verification_screen);
        main_sign_up = v.findViewById(R.id.main_sign_up);
        btn_signup = v.findViewById(R.id.btn_signup);
        btn_resend = v.findViewById(R.id.resend_btn);



        //  ed_progress =  (LinearLayout) v.findViewById(R.id.linlaHeaderProgress);
        back_icon_verification = v.findViewById(R.id.back_icon_verification);
        verification_main_screen = v.findViewById(R.id.verification_main_div);

        confirmation_main_screen = v.findViewById(R.id.confirmation_main_screen);

        div = v.findViewById(R.id.div);
        div.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                return true;
            }
        });

        div2 = v.findViewById(R.id.div2);
        div2.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                return true;
            }
        });

        // EditText Boxes

        editText1 = (EditText) v.findViewById(R.id.edit_text1);
        editText2 = (EditText) v.findViewById(R.id.edit_text2);
        editText3 = (EditText) v.findViewById(R.id.edit_text3);
        editText4 = (EditText) v.findViewById(R.id.edit_text4);
        e_first = (EditText) v.findViewById(R.id.ed_fname);
        e_last = (EditText) v.findViewById(R.id.ed_lname);
        e_email = (EditText) v.findViewById(R.id.ed_email);
        e_password = (EditText) v.findViewById(R.id.ed_password);

        fb_div = v.findViewById(R.id.fb_div);
        fb_btn = (TextView) v.findViewById(R.id.btn_fb);
        login_button_fb = (LoginButton) v.findViewById(R.id.login_button_fb);
        login_button_fb.setReadPermissions(Arrays.asList("email"));

        // If using in a fragment
        login_button_fb.setFragment(this);

        fb_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                login_button_fb.performClick();
                /// Login Method
                FB_LOGIN();

                ////
            }
        });
        fb_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                login_button_fb.performClick();
                /// Login Method
                FB_LOGIN();
            }
        });

        google_sign_in_div = v.findViewById(R.id.google_sign_in_div);
        google_sign_in_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                GoogleSignInAccount acct = GoogleSignIn.getLastSignedInAccount(getContext());
                if (acct != null) {
                    String Fname = acct.getGivenName();
                    String Lname = acct.getFamilyName();
                    String Email = acct.getEmail();

                    e_first.setText(Fname);
                    e_last.setText(Lname);
                    e_email.setText(Email);
                  //  String Password = acct.getId();

                  // SignUp(Email,Password,Fname,Lname);

                  /*  Storedata(Fname,Lname,Email,Password);
                    ed_email.setText(Email);
                    ed_password.setText(Password);*/
                }
                else {
                    Intent signInIntent = mGoogleSignInClient.getSignInIntent();
                    startActivityForResult(signInIntent, 123);
                }
            }
        });




        main_sign_up_div = v.findViewById(R.id.main_sign_up_div);



        editText1.addTextChangedListener(new TextWatcher() {

            public void onTextChanged(CharSequence s, int start, int before, int count) {
                // TODO Auto-generated method stub

                TextView text = (TextView) getActivity().getCurrentFocus();

                if (editText1.getText().toString().length() == 1)     //size as per your requirement
                {
                    editText2.requestFocus();
                }
            }

            public void beforeTextChanged(CharSequence s, int start,
                                          int count, int after) {
                // TODO Auto-generated method stub

            }

            public void afterTextChanged(Editable s) {
                // TODO Auto-generated method stub
            }

        });

        editText2.addTextChangedListener(new TextWatcher() {

            public void onTextChanged(CharSequence s, int start, int before, int count) {
                // TODO Auto-generated method stub

                TextView text = (TextView) getActivity().getCurrentFocus();

                if (editText2.getText().toString().length() == 1)     //size as per your requirement
                {
                    editText3.requestFocus();
                }
            }

            public void beforeTextChanged(CharSequence s, int start,
                                          int count, int after) {
                // TODO Auto-generated method stub

            }

            public void afterTextChanged(Editable s) {
                // TODO Auto-generated method stub
            }

        });

        editText3.addTextChangedListener(new TextWatcher() {

            public void onTextChanged(CharSequence s, int start, int before, int count) {
                // TODO Auto-generated method stub

                TextView text = (TextView) getActivity().getCurrentFocus();

                if (editText3.getText().toString().length() == 1)     //size as per your requirement
                {
                    editText4.requestFocus();
                }
            }

            public void beforeTextChanged(CharSequence s, int start,
                                          int count, int after) {
                // TODO Auto-generated method stub

            }

            public void afterTextChanged(Editable s) {
                // TODO Auto-generated method stub
            }

        });

        /// End

        btn_done = v.findViewById(R.id.btn_done);
        confirm_div = v.findViewById(R.id.confirm_screen);
        back_icon_confirm = v.findViewById(R.id.back_icon_confirm);

        back_icon_confirm.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {


                verification_main_screen.setVisibility(View.VISIBLE);
                confirm_div.setVisibility(View.GONE);

            }
        });


        btn_resend.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {


                Verify();

            }
        });


        btn_done.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                try {
                    InputMethodManager imm = (InputMethodManager) getContext().getSystemService(INPUT_METHOD_SERVICE);
                    imm.hideSoftInputFromWindow(getActivity().getCurrentFocus().getWindowToken(), 0);
                } catch (Exception e) {

                }

                if (countryCode.getText().toString().trim().equals("")) {

                    Toast.makeText(getContext(), "Enter Country!", Toast.LENGTH_SHORT).show();

                } else if (phone.getText(true).toString().trim().equals("")) {

                    Toast.makeText(getContext(), "Enter Contact Number!", Toast.LENGTH_SHORT).show();

                } else {

                    Verify();
                }

            }
        });



        back_icon_verification.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                verification_div.setVisibility(View.GONE);
                main_sign_up.setVisibility(View.VISIBLE);
            }
        });


        btn_signup.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                try {
                    InputMethodManager imm = (InputMethodManager) getContext().getSystemService(INPUT_METHOD_SERVICE);
                    imm.hideSoftInputFromWindow(getActivity().getCurrentFocus().getWindowToken(), 0);
                } catch (Exception e) {

                }


                boolean valid = checkEmail(e_email.getText().toString());
                if (e_first.getText().toString().trim().equals("")) {

                    Toast.makeText(getContext(), "Enter First Name!", Toast.LENGTH_SHORT).show();

                } else if (e_last.getText().toString().trim().equals("")) {

                    Toast.makeText(getContext(), "Enter Last Name!", Toast.LENGTH_SHORT).show();

                } else if (e_email.getText().toString().trim().equals("")) {

                    Toast.makeText(getContext(), "Enter Email!", Toast.LENGTH_SHORT).show();

                } else if (e_password.getText().toString().trim().equals("")) {

                    Toast.makeText(getContext(), "Enter Password!", Toast.LENGTH_SHORT).show();
                } else if (e_password.getText().toString().length() < 6) {

                    Toast.makeText(getContext(), "Enter Password Atleat 6 Charaters!", Toast.LENGTH_SHORT).show();
                } else if (!valid) {

                    Toast.makeText(getContext(), "Enter Valid Email!", Toast.LENGTH_SHORT).show();
                } else {

                    verification_div.setVisibility(View.VISIBLE);
                    main_sign_up.setVisibility(View.GONE);
                    FLAG_VERIFICATION_SCREEN = true;

                    Get_Coutry_list();

                }
            }

        });
        back_sign_up.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                // finish();

                if (UserAccountFragment.LOG_OUT_FLAG) {
                    UserAccountFragment userAccountFragment = new UserAccountFragment();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();
                    transaction.replace(R.id.main_sign_up_div, userAccountFragment);
                    transaction.addToBackStack(null);
                    transaction.commit();
                    UserAccountFragment.LOG_OUT_FLAG = false;
                    LoginManager.getInstance().logOut();
                } else {
                    Fragment restaurantMenuItemsFragment = new LoginAcitvity();
                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                    transaction.add(R.id.main_sign_up_div, restaurantMenuItemsFragment, "ParentFragment").commit();
                    LoginManager.getInstance().logOut();
                }

                try {
                    InputMethodManager imm = (InputMethodManager) getContext().getSystemService(INPUT_METHOD_SERVICE);
                    imm.hideSoftInputFromWindow(getActivity().getCurrentFocus().getWindowToken(), 0);
                } catch (Exception e) {

                }
            }
        });


        //Registration Ends
        confirm_btn = v.findViewById(R.id.confirm_btn);
        confirm_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if (editText1.getText().toString().trim().equals("") || editText2.getText().toString().trim().equals("") || editText3.getText().toString().trim().equals("") || editText4.getText().toString().trim().equals("")) {

                    Toast.makeText(getContext(), "Enter Code!", Toast.LENGTH_SHORT).show();

                } else {

                    Verify2();
                }

            }
        });


        return v;
    }


    public void FB_LOGIN() {

        // Callback registration
        login_button_fb.registerCallback(callbackManager, new FacebookCallback<LoginResult>() {
            @Override
            public void onSuccess(LoginResult loginResult) {

                final AccessToken accessToken = loginResult.getAccessToken();
                // final String id = Profile.getCurrentProfile().getId().toString();
                GraphRequest request = GraphRequest.newMeRequest(accessToken, new GraphRequest.GraphJSONObjectCallback() {
                    @Override
                    public void onCompleted(JSONObject user, GraphResponse graphResponse) {
                        String useremail = user.optString("email");

                        String FName = user.optString("first_name");
                        String LName = user.optString("last_name");
                        String ID = user.optString("id");

                        if(FName!=null&& LName!=null) {

                            e_first.setText(FName);
                            e_last.setText(LName);
                            e_email.setText(useremail);

                            //SignUp(useremail, ID, FName, LName);
                        }
                        else {
                            Toast.makeText(getContext(),"Could not Login because Your ID does not Contain: First Or Last Name",Toast.LENGTH_LONG).show();
                        }

                    }
                });
                Bundle parameters = new Bundle();
                parameters.putString("fields", "last_name,first_name,email");
                request.setParameters(parameters);
                request.executeAsync();


            }

            @Override
            public void onCancel() {
                // App code
                Toast.makeText(getContext(), "Cancle", Toast.LENGTH_SHORT).show();
            }

            @Override
            public void onError(FacebookException exception) {
                // App code
                Toast.makeText(getContext(), "Error", Toast.LENGTH_SHORT).show();
            }
        });

    }


    private boolean checkEmail(String email) {
        return EMAIL_ADDRESS_PATTERN.matcher(email).matches();
    }




    String [] countryname_list;
    String [] countrycode_list;
    private void Get_Coutry_list() {
        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout, false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);


        RequestQueue queue = Volley.newRequestQueue(getContext());
        String url = Config.showCountries;
        JSONObject jsonObject = new JSONObject();

        Log.d("JSONPost",jsonObject.toString());
        JsonObjectRequest jsonObjReq = new JsonObjectRequest(Request.Method.POST,
                url, jsonObject,
                new Response.Listener<JSONObject>() {

                    @Override
                    public void onResponse(JSONObject response) {

                        Log.d("JSONPost", response.toString());
                        String strJson = response.toString();
                        JSONObject jsonResponse = null;
                        try {
                            jsonResponse = new JSONObject(strJson);

                            Log.d("JSONPost", jsonResponse.toString());

                            int code_id = Integer.parseInt(jsonResponse.optString("code"));

                            if (code_id == 200) {

                                JSONArray countries=jsonResponse.optJSONArray("countries");

                                countryname_list=new String[countries.length()];
                                countrycode_list=new String[countries.length()];

                                for(int i=0;i<countries.length();i++){
                                    JSONObject county=countries.optJSONObject(i);
                                    JSONObject tax=county.optJSONObject("Tax");
                                    countryname_list[i]=tax.optString("country");
                                    countrycode_list[i]=tax.optString("country_code");
                                }

                                ArrayAdapter<String> adapter = new ArrayAdapter<String>(getContext(),
                                        android.R.layout.simple_spinner_item, countryname_list);
                                adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                                spinner.setAdapter(adapter);
                                spinner.setOnItemSelectedListener(SingUpActivity.this);

                            }

                        } catch (JSONException e) {
                            e.printStackTrace();
                        }

                        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout, true);
                        transparent_layer.setVisibility(View.GONE);
                        progressDialog.setVisibility(View.GONE);

                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout, true);
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

        queue.add(jsonObjReq);
    }

    public void onItemSelected(AdapterView<?> parent, View v, int position, long id) {

        countryCode.setText(countrycode_list[position]);
    }

    @Override
    public void onNothingSelected(AdapterView<?> adapterView) {

    }




    private void Verify() {
        //Getting values from edit texts

        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout, false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);

        String phne = phone.getText(true).toString().replaceAll("[^0-9]", "");


        //Creating a string request
        RequestQueue queue = Volley.newRequestQueue(getContext());
        String url = Config.Verify_URL;
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("verify", "0");
            jsonObject.put("phone_no", countryCode.getText().toString() + phne);

        } catch (JSONException e) {
            e.printStackTrace();
        }
        Log.d("JSONPost",jsonObject.toString());
        JsonObjectRequest jsonObjReq = new JsonObjectRequest(Request.Method.POST,
                url, jsonObject,
                new Response.Listener<JSONObject>() {

                    @Override
                    public void onResponse(JSONObject response) {

                        Log.d("JSONPost", response.toString());
                        String strJson = response.toString();
                        JSONObject jsonResponse = null;
                        try {
                            jsonResponse = new JSONObject(strJson);

                            Log.d("JSONPost", jsonResponse.toString());

                            int code_id = Integer.parseInt(jsonResponse.optString("code"));

                            if (code_id == 200) {

                                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout, true);
                                transparent_layer.setVisibility(View.GONE);
                                progressDialog.setVisibility(View.GONE);
                                verification_main_screen.setVisibility(View.GONE);
                                confirm_div.setVisibility(View.VISIBLE);
                                FLAG_CONFIRMATION_SCREEN = true;

                            } else {

                                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout, true);
                                transparent_layer.setVisibility(View.GONE);
                                progressDialog.setVisibility(View.GONE);
                                JSONObject json = new JSONObject(jsonResponse.toString());
                              //  Toast.makeText(getContext(), json.optString("msg"), Toast.LENGTH_SHORT).show();
                            }

                            //JSONArray jsonMainNode = jsonResponse.optJSONArray("msg");                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }


                        //pDialog.hide();
                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout, true);
                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
                //   Toast.makeText(getContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
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

// Add the request to the RequestQueue.
        queue.add(jsonObjReq);
    }

    private void Verify2() {
        //Getting values from edit texts

        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout, true);
        transparent_layer.setVisibility(View.GONE);
        progressDialog.setVisibility(View.GONE);

        String phne = phone.getText(true).toString().replaceAll("[^0-9]", "");


        //Creating a string request
        RequestQueue queue = Volley.newRequestQueue(getContext());

        String url = Config.Verify_URL;

        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("verify", "1");
            jsonObject.put("phone_no", countryCode.getText().toString() + phne);
           jsonObject.put("code", editText1.getText().toString() + editText2.getText().toString() + editText3.getText().toString() + editText4.getText().toString());


        } catch (JSONException e) {
            e.printStackTrace();
        }
// Request a string response from the provided URL.
        JsonObjectRequest jsonObjReq = new JsonObjectRequest(Request.Method.POST,
                url, jsonObject,
                new Response.Listener<JSONObject>() {

                    @Override
                    public void onResponse(JSONObject response) {

                        Log.d("JSONPost", response.toString());
                        String strJson = response.toString();
                        JSONObject jsonResponse = null;
                        try {
                            jsonResponse = new JSONObject(strJson);

                            Log.d("JSONPost", jsonResponse.toString());

                            int code_id = Integer.parseInt(jsonResponse.optString("code"));

                            if (code_id == 200) {

                                SignUp(e_email.getText().toString(), e_password.getText().toString(), e_first.getText().toString(), e_last.getText().toString());

                            } else {


                                JSONObject json = new JSONObject(jsonResponse.toString());
                              //  Toast.makeText(getContext(), json.optString("msg"), Toast.LENGTH_SHORT).show();
                            }

                            //JSONArray jsonMainNode = jsonResponse.optJSONArray("msg");                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }


                        //pDialog.hide();
                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout, true);
                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
                //Toast.makeText(getContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
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

// Add the request to the RequestQueue.
        queue.add(jsonObjReq);
    }

    private void SignUp(final String email, final String pass, String f_name, String l_name) {
        //Getting values from edit texts
        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout, false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);

        String phne = phone.getText(true).toString().replaceAll("[^0-9]", "");


        //Creating a string request
        RequestQueue queue = Volley.newRequestQueue(getContext());
        String url = Config.SignUp_URL;
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("email", email);
            jsonObject.put("password", pass);
            jsonObject.put("device_token", "123");
            jsonObject.put("first_name", f_name);
            jsonObject.put("last_name", l_name);
            jsonObject.put("phone", countryCode.getText().toString() + phne);
            jsonObject.put("role", "user");


        } catch (JSONException e) {
            e.printStackTrace();
        }
// Request a string response from the provided URL.
        JsonObjectRequest jsonObjReq = new JsonObjectRequest(Request.Method.POST,
                url, jsonObject,
                new Response.Listener<JSONObject>() {

                    @Override
                    public void onResponse(JSONObject response) {

                        Log.d("JSONPost", response.toString());
                        String strJson = response.toString();
                        JSONObject jsonResponse = null;
                        try {
                            jsonResponse = new JSONObject(strJson);

                            Log.d("JSONPost", jsonResponse.toString());

                            int code_id = Integer.parseInt(jsonResponse.optString("code"));

                            if (code_id == 200) {

                                login(email,pass);


                            } else {
                                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout, true);
                                transparent_layer.setVisibility(View.GONE);
                                progressDialog.setVisibility(View.GONE);
                                JSONObject json = new JSONObject(jsonResponse.toString());
                               // Toast.makeText(getContext(), json.optString("msg"), Toast.LENGTH_SHORT).show();
                            }

                            //JSONArray jsonMainNode = jsonResponse.optJSONArray("msg");                            }
                        } catch (JSONException e) {
                            e.printStackTrace();

                            TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout, true);
                            transparent_layer.setVisibility(View.GONE);
                            progressDialog.setVisibility(View.GONE);
                        }


                        //pDialog.hide();
                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout, true);
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

        queue.add(jsonObjReq);
    }



    private void login(final String email, final String pass){
        //Getting values from edit texts

        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);


        String _lat = preferences.getString(PreferenceClass.LATITUDE,"");
        String _long = preferences.getString(PreferenceClass.LONGITUDE,"");
        String device_tocken = FirebaseInstanceId.getInstance().getToken();

        //Creating a string request
        RequestQueue queue = Volley.newRequestQueue(getContext());
        String url = Config.LOGIN_URL;
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("email", email);
            jsonObject.put("password", pass);
            jsonObject.put("device_token", device_tocken);

            if(_lat.isEmpty()){
                jsonObject.put("lat", "31.5042483");
            }else {
                jsonObject.put("lat", _lat);
            }
            if(_long.isEmpty()){
                jsonObject.put("long", "74.3307944");
            }else {
                jsonObject.put("long", _long);
            }

        } catch (JSONException e) {
            e.printStackTrace();
        }
// Request a string response from the provided URL.
        JsonObjectRequest jsonObjReq = new JsonObjectRequest(Request.Method.POST,
                url,jsonObject,
                new Response.Listener<JSONObject>() {

                    @Override
                    public void onResponse(JSONObject response) {

                        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                        transparent_layer.setVisibility(View.GONE);
                        progressDialog.setVisibility(View.GONE);

                        Log.d("JSONPost_login", response.toString());
                        String strJson =  response.toString();
                        JSONObject jsonResponse = null;
                        try {
                            jsonResponse = new JSONObject(strJson);

                            int code_id  = Integer.parseInt(jsonResponse.optString("code"));

                            if(code_id == 200) {

                                JSONObject json = new JSONObject(jsonResponse.toString());
                                JSONObject resultObj = json.getJSONObject("msg");
                                JSONObject json1 = new JSONObject(resultObj.toString());
                                JSONObject resultObj1 = json1.getJSONObject("UserInfo");
                                JSONObject resultObj2 = json1.getJSONObject("User");
                                JSONObject resultObj3 = json1.getJSONObject("Admin");

                                SharedPreferences.Editor editor = preferences.edit();
                                editor.putString(PreferenceClass.pre_email, email);
                                editor.putString(PreferenceClass.pre_pass, pass);
                                editor.putString(PreferenceClass.pre_first, resultObj1.optString("first_name"));
                                editor.putString(PreferenceClass.pre_last, resultObj1.optString("last_name"));
                                editor.putString(PreferenceClass.pre_contact, resultObj1.optString("phone"));
                                editor.putString(PreferenceClass.pre_user_id, resultObj1.optString("user_id"));
                                String admin_user_id=resultObj3.optString("user_id");
                                editor.putString(PreferenceClass.ADMIN_USER_ID,admin_user_id);
                                editor.putString(PreferenceClass.ADMIN_PHONE_NUMBER,resultObj3.optString("phone"));

                                editor.putBoolean(PreferenceClass.IS_LOGIN, true);
                                editor.commit();

                                OrderDetailFragment.CALLBACK_ORDERFRAG = true;

                                if(resultObj2.optString("role").equalsIgnoreCase("rider")){

                                    editor.putString(PreferenceClass.USER_TYPE,resultObj2.optString("role"));
                                    editor.commit();
                                    startActivity(new Intent(getContext(),RiderMainActivity.class));
                                    getActivity().finish();

                                }

                                else if(resultObj2.optString("role").equalsIgnoreCase("user")) {

                                    editor.putString(PreferenceClass.USER_TYPE,resultObj2.optString("role"));
                                    editor.commit();
                                    if(CartFragment.CART_LOGIN){

                                        startActivity(new Intent(getContext(), MainActivity.class));
                                        getActivity().finish();
                                    }
                                    else {
                                        startActivity(new Intent(getContext(), MainActivity.class));
                                        getActivity().finish();
                                    }


                                }

                                else  if(resultObj2.optString("role").equalsIgnoreCase("hotel")){

                                    editor.putString(PreferenceClass.USER_TYPE,resultObj2.optString("role"));
                                    editor.commit();
                                    startActivity(new Intent(getContext(),HotelMainActivity.class));
                                    getActivity().finish();

                                }

                            }else{

                                try  {
                                    InputMethodManager imm = (InputMethodManager)getContext().getSystemService(INPUT_METHOD_SERVICE);
                                    imm.hideSoftInputFromWindow(getActivity().getCurrentFocus().getWindowToken(), 0);
                                } catch (Exception e) {

                                }

                                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                                transparent_layer.setVisibility(View.GONE);
                                progressDialog.setVisibility(View.GONE);
                                JSONObject json = new JSONObject(jsonResponse.toString());
                                Toast.makeText(getContext(),json.optString("msg"), Toast.LENGTH_SHORT).show();
                            }

                            //JSONArray jsonMainNode = jsonResponse.optJSONArray("msg");                            }
                        } catch (JSONException e) {
                            e.printStackTrace();

                        }

                        //pDialog.hide();
                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
                // Toast.makeText(getContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
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

// Add the request to the RequestQueue.
        queue.add(jsonObjReq);
    }



    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        callbackManager.onActivityResult(requestCode, resultCode, data);
        super.onActivityResult(requestCode, resultCode, data);
        //login with Gmail
        if (requestCode == 123) {
            GoogleSignInResult result = Auth.GoogleSignInApi.getSignInResultFromIntent(data);
            handleSignInResult(result);

            //  handleSignInResult(task);
        } else {
            login_button_fb.registerCallback(callbackManager, new FacebookCallback<LoginResult>() {
                @Override
                public void onSuccess(LoginResult loginResult) {
                FB_LOGIN();
                }

                @Override
                public void onCancel() {
                    // App code
                    Toast.makeText(getContext(), "Cancle", Toast.LENGTH_SHORT).show();
                }

                @Override
                public void onError(FacebookException exception) {
                    // App code
                    Toast.makeText(getContext(), "Error", Toast.LENGTH_SHORT).show();
                }
            });
        }

    }

    private void handleSignInResult(GoogleSignInResult result) {
        Log.d("handleSignInResult", "handleSignInResult:" + result.isSuccess());
        if (result.isSuccess()) {
            // Signed in successfully, show authenticated UI.
            GoogleSignInAccount acct = result.getSignInAccount();

            //   Log.e(TAG, "display name: " + acct.getDisplayName());

            String Fname = acct.getGivenName();
            String Lname = acct.getFamilyName();
            String Email = acct.getEmail();
            String Password = acct.getId();
            e_first.setText(Fname);
            e_last.setText(Lname);
            e_email.setText(Email);

           // SignUp(Email,Password,Fname,Lname);
        }
    }


}
