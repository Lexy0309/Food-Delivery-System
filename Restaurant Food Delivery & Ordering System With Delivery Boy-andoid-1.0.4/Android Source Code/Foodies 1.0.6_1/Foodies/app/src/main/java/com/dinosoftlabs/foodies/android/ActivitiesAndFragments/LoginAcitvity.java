package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;

import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.RelativeLayout;
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
import com.facebook.AccessToken;
import com.facebook.GraphRequest;
import com.facebook.GraphResponse;
import com.facebook.Profile;
import com.gmail.samehadar.iosdialog.CamomileSpinner;
import com.google.android.gms.auth.api.Auth;
import com.google.android.gms.auth.api.signin.GoogleSignIn;
import com.google.android.gms.auth.api.signin.GoogleSignInAccount;
import com.google.android.gms.auth.api.signin.GoogleSignInClient;
import com.google.android.gms.auth.api.signin.GoogleSignInOptions;
import com.google.android.gms.auth.api.signin.GoogleSignInResult;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.firebase.iid.FirebaseInstanceId;
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.HActivitiesAndFragment.HotelMainActivity;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RiderMainActivity;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;
import com.facebook.CallbackManager;
import com.facebook.FacebookCallback;
import com.facebook.FacebookException;
import com.facebook.FacebookSdk;
import com.facebook.appevents.AppEventsLogger;
import com.facebook.login.LoginManager;
import com.facebook.login.LoginResult;
import com.facebook.login.widget.LoginButton;
import com.dinosoftlabs.foodies.android.Utils.TabLayoutUtils;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.Arrays;
import java.util.HashMap;
import java.util.Map;
import java.util.regex.Pattern;

import static android.content.Context.INPUT_METHOD_SERVICE;
import static com.dinosoftlabs.foodies.android.ActivitiesAndFragments.AddressListFragment.CART_NOT_LOAD;


/**
 * Created by RaoMudassar on 12/5/17.
 */

public class LoginAcitvity extends Fragment implements View.OnClickListener,GoogleApiClient.OnConnectionFailedListener,
            GoogleApiClient.ConnectionCallbacks{

    SharedPreferences sPref;
    RelativeLayout fb_div;

    public static boolean LOGIN_FLAG;

    FrameLayout fb_login_layout,login_main_div;

    CamomileSpinner logInProgress;
    RelativeLayout transparent_layer,progressDialog,google_sign_in_div;

    Button log_in_now,btn_google;
    TextView fb_btn;

    TextView loginText,tv_email,tv_pass,sign_up_txt,tv_forget_password,tv_signed_up_now,tv_sign_up;

    EditText ed_email,ed_password;
    LoginButton login_button_fb;

    ImageView back_icon;


    public static final Pattern EMAIL_ADDRESS_PATTERN = Pattern.compile(
            "[a-zA-Z0-9\\+\\.\\_\\%\\-\\+]{1,256}" +
                    "\\@" +
                    "[a-zA-Z0-9][a-zA-Z0-9\\-]{0,64}" +
                    "(" +
                    "\\." +
                    "[a-zA-Z0-9][a-zA-Z0-9\\-]{0,25}" +
                    ")+"
    );

    CallbackManager callbackManager;
    public static GoogleSignInClient  mGoogleSignInClient;


    @SuppressWarnings("deprecation")
    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.login_activity, container, false);
        getActivity().getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_STATE_VISIBLE|WindowManager.LayoutParams.SOFT_INPUT_ADJUST_RESIZE
                |WindowManager.LayoutParams.SOFT_INPUT_STATE_HIDDEN);
        getActivity().getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_HIDDEN);
        getActivity().getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_ADJUST_PAN);


        sPref = getContext().getSharedPreferences(PreferenceClass.user,Context.MODE_PRIVATE);


        ed_email = (EditText)v.findViewById(R.id.ed_email);
        ed_password =(EditText)v.findViewById(R.id.ed_password);
        log_in_now = (Button)v.findViewById(R.id.btn_login);


        // Google SignIn Initialize

        String serverClientId = getResources().getString(R.string.google_api_client_id);

        // Configure Google Sign In
        GoogleSignInOptions gso = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
                .requestIdToken(getString(R.string.default_web_client_id))
                .requestEmail()
                .build();
        mGoogleSignInClient = GoogleSignIn.getClient(getContext(), gso);
    //    signInButton = v.findViewById(R.id.sign_in_button);

        // End//

        /// FB Login
        FacebookSdk.sdkInitialize(getContext());
        AppEventsLogger.activateApp(getContext());

        callbackManager = CallbackManager.Factory.create();

        /// End

        tv_sign_up = v.findViewById(R.id.tv_sign_up);
        tv_signed_up_now = v.findViewById(R.id.tv_signed_up_now);
        FontHelper.applyFont(getContext(),tv_sign_up, AllConstants.verdana);

        fb_btn = v.findViewById(R.id.fb_btn);
        fb_div = v.findViewById(R.id.fb_div);
        fb_div.setOnClickListener(this);
        fb_btn.setOnClickListener(this);
        login_button_fb = (LoginButton) v.findViewById(R.id.login_button_fb);
        login_button_fb.setReadPermissions(Arrays.asList("email"));

        // If using in a fragment
        login_button_fb.setFragment(this);
        // Callback registration

        back_icon = v.findViewById(R.id.back_icon);
        back_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                try  {
                    InputMethodManager imm = (InputMethodManager)getContext().getSystemService(INPUT_METHOD_SERVICE);
                    imm.hideSoftInputFromWindow(getActivity().getCurrentFocus().getWindowToken(), 0);
                } catch (Exception e) {

                }

                UserAccountFragment userAccountFragment = new UserAccountFragment();
                FragmentTransaction transaction = getFragmentManager().beginTransaction();
                transaction.replace(R.id.login_main_div, userAccountFragment);
                transaction.addToBackStack(null);
                transaction.commit();
                LoginManager.getInstance().logOut();
               /* CartFragment.CART_ADDRESS = true;
                CartFragment.CART_PAYMENT_METHOD = true;*/
                //LOGIN_FLAG = true;
            }
        });


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

                        ed_email.setText(useremail);

                        //login(useremail,ID);

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
                Toast.makeText(getContext(),"Cancle",Toast.LENGTH_SHORT).show();
            }

            @Override
            public void onError(FacebookException exception) {
                // App code
                Toast.makeText(getContext(),"Error",Toast.LENGTH_SHORT).show();
            }
        });

        login_main_div = v.findViewById(R.id.login_main_div);
        login_main_div.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                InputMethodManager imm = (InputMethodManager) getContext().getSystemService(
                        Activity.INPUT_METHOD_SERVICE);
                imm.toggleSoftInput(InputMethodManager.HIDE_IMPLICIT_ONLY, 0);

                return false;
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
                    String Password = acct.getId();

                    ed_email.setText(Email);

                    //login(Email,Password);


                }
                else {
                    Intent signInIntent = mGoogleSignInClient.getSignInIntent();
                    startActivityForResult(signInIntent, 123);
                }
            }
        });



        tv_email = (TextView)v.findViewById(R.id.tv_email);
        tv_pass = (TextView)v.findViewById(R.id.tv_password);
        sign_up_txt = (TextView)v.findViewById(R.id.tv_sign_up);

        logInProgress = v.findViewById(R.id.logInProgress);
        logInProgress.start();
        progressDialog = v.findViewById(R.id.progressDialog);
        transparent_layer = v.findViewById(R.id.transparent_layer);

        loginText = (TextView)v.findViewById(R.id.login_title);
        tv_forget_password = v.findViewById(R.id.tv_forget_password);
        tv_forget_password.setOnClickListener(this);
        FontHelper.applyFont(getContext(),tv_forget_password, AllConstants.arial);

        //

        log_in_now.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                boolean valid = checkEmail(ed_email.getText().toString());

                if (ed_email.getText().toString().trim().equals("")) {

                    Toast.makeText(getContext(), "Enter Email!", Toast.LENGTH_SHORT).show();

                } else if (ed_password.getText().toString().trim().equals("")) {

                    Toast.makeText(getContext(), "Enter Password!", Toast.LENGTH_SHORT).show();
                }else if (ed_password.getText().toString().length()<6) {

                    Toast.makeText(getContext(), "Enter Password Atleat 6 Charaters!", Toast.LENGTH_SHORT).show();
                }
                else if (!valid) {

                    Toast.makeText(getContext(), "Enter Valid Email!", Toast.LENGTH_SHORT).show();
                }else {

                   String this_email = ed_email.getText().toString();
                   String this_password = ed_password.getText().toString();
                    CART_NOT_LOAD = true;
                    login(this_email,this_password);

                }
            }
        });

//        btn_sign_up_now.setTypeface(TypeFace.getTypeface(getApplicationContext(),TypeFace.arial))


        tv_signed_up_now.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                Fragment restaurantMenuItemsFragment = new SingUpActivity();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.login_main_div, restaurantMenuItemsFragment,"parent").commit();

                LoginManager.getInstance().logOut();
            }
        });





        return v;
    }

    private void login(String email,String pass){
        //Getting values from edit texts
        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);


        String _lat = sPref.getString(PreferenceClass.LATITUDE,"");
        String _long = sPref.getString(PreferenceClass.LONGITUDE,"");
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
                                JSONObject resultObj2 = json1.getJSONObject("User");
                                JSONObject resultObj3 = json1.getJSONObject("Admin");

                                SharedPreferences.Editor editor = sPref.edit();
                                editor.putString(PreferenceClass.pre_email, ed_email.getText().toString());
                                editor.putString(PreferenceClass.pre_pass, ed_password.getText().toString());
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


    public void googleSignIn(){

    }

    private boolean checkEmail(String email) {
        return EMAIL_ADDRESS_PATTERN.matcher(email).matches();
    }


    @Override
    public void onClick(View view) {
        if(view == fb_div){
            login_button_fb.performClick();
        }
        if(view==fb_btn){
            login_button_fb.performClick();
        }
        else if(view==tv_forget_password){

            Fragment restaurantMenuItemsFragment = new RecoverPasswordFragment();
            FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
            transaction.add(R.id.login_main_div, restaurantMenuItemsFragment,"parent").commit();
         //   LOGIN_FLAG = true;

        }
    }

    @Override
    public void onResume() {
        super.onResume();
    }

    private void handleSignInResult(GoogleSignInResult result) {
        Log.d("handleSignInResult", "handleSignInResult:" + result.isSuccess());
        if (result.isSuccess()) {
            // Signed in successfully, show authenticated UI.
            GoogleSignInAccount acct = result.getSignInAccount();

            //   Log.e(TAG, "display name: " + acct.getDisplayName());

            String personName = acct.getDisplayName();
            String personPhotoUrl = acct.getPhotoUrl().toString();
            String email = acct.getEmail();
        }
    }

    public void Storedata(String fname,String lname,String email,String password){

    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        callbackManager.onActivityResult(requestCode, resultCode, data);
        super.onActivityResult(requestCode, resultCode, data);
        //login with Gmail
        if(requestCode==123){
            GoogleSignInResult result = Auth.GoogleSignInApi.getSignInResultFromIntent(data);
            handleSignInResult(result);

          //  handleSignInResult(task);
        }
        else {
            login_button_fb.registerCallback(callbackManager, new FacebookCallback<LoginResult>() {
                @Override
                public void onSuccess(LoginResult loginResult) {

                    final AccessToken accessToken = loginResult.getAccessToken();
                    final String password = Profile.getCurrentProfile().getId().toString();
                    GraphRequest request = GraphRequest.newMeRequest(accessToken, new GraphRequest.GraphJSONObjectCallback() {
                        @Override
                        public void onCompleted(JSONObject user, GraphResponse graphResponse) {
                            String useremail = user.optString("email");
                            String FName = user.optString("first_name");
                            String LName = user.optString("last_name");

                            // App code
                            Toast.makeText(getContext(), "SuccessFull", Toast.LENGTH_SHORT).show();
                        }
                    });
                }

                @Override
                public void onCancel() {
                    // App code
                    Toast.makeText(getContext(),"Cancle",Toast.LENGTH_SHORT).show();
                }

                @Override
                public void onError(FacebookException exception) {
                    // App code
                    Toast.makeText(getContext(),"Error",Toast.LENGTH_SHORT).show();
                }
            });
        }
      /*  else if(mCallbackManager.onActivityResult(requestCode, resultCode, data)){
            return;
        }*/
    }

    @Override
    public void onConnectionFailed(@NonNull ConnectionResult connectionResult) {

    }

    @Override
    public void onConnected(@Nullable Bundle bundle) {

    }

    @Override
    public void onConnectionSuspended(int i) {

    }


   /*

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        LoginManager.getInstance().logOut();
    }

    */


}
