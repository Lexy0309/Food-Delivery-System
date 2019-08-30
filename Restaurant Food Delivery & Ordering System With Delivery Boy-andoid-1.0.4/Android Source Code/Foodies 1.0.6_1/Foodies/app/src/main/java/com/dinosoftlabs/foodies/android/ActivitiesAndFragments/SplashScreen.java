package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.Manifest;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.content.pm.Signature;
import android.location.Address;
import android.location.Geocoder;
import android.location.Location;
import android.location.LocationManager;
import android.os.Bundle;
import android.os.Handler;
import android.provider.Settings;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.ActivityCompat;
import android.support.v4.content.ContextCompat;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.util.Base64;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.dinosoftlabs.foodies.android.BuildConfig;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.GoogleMapWork.MapsActivity;
import com.dinosoftlabs.foodies.android.HActivitiesAndFragment.HotelMainActivity;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RiderMainActivity;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GooglePlayServicesUtil;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationServices;
import com.google.firebase.database.FirebaseDatabase;

import java.io.Console;
import java.io.IOException;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.List;
import java.util.Locale;

/**
 * Created by RaoMudassar on 12/4/17.
 */

public class SplashScreen extends AppCompatActivity implements GoogleApiClient.OnConnectionFailedListener,GoogleApiClient.ConnectionCallbacks {

    private Location mLastLocation;

    // boolean flag to toggle periodic location updates
    private boolean mRequestingLocationUpdates = false;

    private LocationRequest mLocationRequest;

    public static String VERSION_CODE;

    // Splash screen timer
    private static int SPLASH_TIME_OUT = 3000;

    TextView welcome_location_txt;
    private RelativeLayout main_welcome_screen_layout, main_splash_layout, welcome_search_div;

    //  ProgressDialog pd;
    String getCurrentLocationAddress;
    private GoogleApiClient mGoogleApiClient;
    private LocationManager locationManager;
    private String provider;

    SharedPreferences sharedPreferences;
    double latitude, longitude;
    private int PLACE_PICKER_REQUEST = 1;
    private Button welcome_show_restaurants_btn;
    AlertDialog.Builder builder;

    private static final int PERMISSIONS_REQUEST_ACCESS_FINE_LOCATION = 1;
    private static final int PERMISSION_DATA_ACCESS_CODE = 2;
    private boolean mLocationPermissionGranted;


    private final static int PLAY_SERVICES_RESOLUTION_REQUEST = 1000;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        try {
            FirebaseDatabase.getInstance().setPersistenceEnabled(true);
        } catch (RuntimeException e) {
            e.getMessage();
        }
        setContentView(R.layout.splash);
        sharedPreferences = getSharedPreferences(PreferenceClass.user, MODE_PRIVATE);
        getCurrentLocationAddress = sharedPreferences.getString(PreferenceClass.CURRENT_LOCATION_ADDRESS, "");

        VERSION_CODE = BuildConfig.VERSION_NAME;

        /*pd = new ProgressDialog(SplashScreen.this);
        pd.setTitle("Getting your current location");
        pd.show();*/

        // First we need to check availability of play services



      //  if (checkPlayServices()) {
            buildGoogleApiClient();
            LocationRequest locationRequest = LocationRequest.create();
            locationRequest.setPriority(LocationRequest.PRIORITY_HIGH_ACCURACY);
            locationRequest.setInterval(5000);
            locationRequest.setFastestInterval(1000);
            // Building the GoogleApi client



        welcome_location_txt = findViewById(R.id.welcome_location_txt);
        main_welcome_screen_layout = findViewById(R.id.main_welcome_screen_layout);
        main_splash_layout = findViewById(R.id.main_splash_layout);
        welcome_search_div = findViewById(R.id.welcome_search_div);
        welcome_show_restaurants_btn = findViewById(R.id.welcome_show_restaurants_btn);
        welcome_show_restaurants_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent i = new Intent(SplashScreen.this, MainActivity.class);
                startActivity(i);
                final String android_id = Settings.Secure.getString(getContentResolver(),
                        Settings.Secure.ANDROID_ID);
                SharedPreferences.Editor editor2 = sharedPreferences.edit();
                editor2.putString(PreferenceClass.UDID, android_id).commit();
                SharedPreferences.Editor editor = sharedPreferences.edit();
                editor.putString(PreferenceClass.CURRENT_LOCATION_LAT_LONG, latitude + "," + longitude);
                editor.putString(PreferenceClass.CURRENT_LOCATION_ADDRESS, "Kalma Chowk" + " " + "Lahore");
                editor.putString(PreferenceClass.LATITUDE, String.valueOf(latitude));
                editor.putString(PreferenceClass.LONGITUDE, String.valueOf(longitude));
                editor.commit();

                MapsActivity.SAVE_LOCATION = false;
                finish();
                System.out.println("Current time => -----------------------");
            }
        });


        /// check for GPS
      /*  final LocationManager manager = (LocationManager) getSystemService( Context.LOCATION_SERVICE );

        if ( !manager.isProviderEnabled( LocationManager.GPS_PROVIDER ) ) {
            buildAlertMessageNoGps();
        }
        else if(manager.isProviderEnabled( LocationManager.GPS_PROVIDER )){
            getCurrentLocation();
        }*/
        /// End

    /*    mGoogleApiClient = new GoogleApiClient
                .Builder(getApplicationContext())
                .addApi(Places.GEO_DATA_API)
                .addApi(Places.PLACE_DETECTION_API)
                .enableAutoManage(this, this)
                .build();
        mGoogleApiClient.connect();*/
        welcome_search_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                Intent i = new Intent(SplashScreen.this, MapsActivity.class);
                startActivityForResult(i, PERMISSION_DATA_ACCESS_CODE);
               /* PlacePicker.IntentBuilder builder = new PlacePicker.IntentBuilder();
                try {
                    startActivityForResult(builder.build(SplashScreen.this), PLACE_PICKER_REQUEST);
                } catch (GooglePlayServicesRepairableException | GooglePlayServicesNotAvailableException e) {
                    e.printStackTrace();
                }*/
            }
        });

        /// Getting Shared Pref

        if (!getCurrentLocationAddress.isEmpty()) {

            main_welcome_screen_layout.setVisibility(View.GONE);
            main_splash_layout.setVisibility(View.VISIBLE);
            //pd.dismiss();
            new Handler().postDelayed(new Runnable() {

                @Override
                public void run() {
                    // This method will be executed once the timer is over
                    // Start your app main activity
                    String getUserType = sharedPreferences.getString(PreferenceClass.USER_TYPE, "");
                    boolean getLoINSession = sharedPreferences.getBoolean(PreferenceClass.IS_LOGIN, false);

                    if (!getLoINSession) {
                        Intent i = new Intent(SplashScreen.this, MainActivity.class);
                        startActivity(i);
                        finish();
                    }
                    else {

                        if (getUserType.equalsIgnoreCase("rider")) {
                            Intent i = new Intent(SplashScreen.this, RiderMainActivity.class);
                            startActivity(i);
                            finish();
                        } else if (getUserType.equalsIgnoreCase("user")) {
                            Intent i = new Intent(SplashScreen.this, MainActivity.class);
                            startActivity(i);
                            finish();
                        } else if (getUserType.equalsIgnoreCase("hotel")) {
                            Intent i = new Intent(SplashScreen.this, HotelMainActivity.class);
                            startActivity(i);
                            finish();
                        }

                    }
                }
            }, SPLASH_TIME_OUT);

        } else {

              displayLocation();

        }


        printKeyHash();
    }

   /* public void getCurrentLocation() {
        LocationManager locationManager;
        String context = Context.LOCATION_SERVICE;
        locationManager = (LocationManager) getSystemService(context);
        Criteria crta = new Criteria();
        crta.setAccuracy(Criteria.ACCURACY_FINE);
        crta.setAltitudeRequired(false);
        crta.setBearingRequired(false);
        crta.setCostAllowed(true);
        crta.setPowerRequirement(Criteria.POWER_LOW);
        String provider = locationManager.getBestProvider(crta, true);


        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            // TODO: Consider calling
            //    ActivityCompat#requestPermissions
            // here to request the missing permissions, and then overriding
            //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
            //                                          int[] grantResults)
            // to handle the case where the user grants the permission. See the documentation
            // for ActivityCompat#requestPermissions for more details.
            return;
        }
        locationManager.requestLocationUpdates(provider, 1000, 0,
                new LocationListener() {
                    @Override
                    public void onStatusChanged(String provider, int status, Bundle extras) {
                    }

                    @Override
                    public void onProviderEnabled(String provider) {
                    }

                    @Override
                    public void onProviderDisabled(String provider) {
                    }

                    @Override
                    public void onLocationChanged(Location location) {
                        if (location != null) {
                            latitude = location.getLatitude();
                            longitude = location.getLongitude();
                            try{
                            if (longitude != 0.0 && latitude != 0.0) {
                                System.out.println("WE GOT THE LOCATION");
                                System.out.println(latitude);
                                System.out.println(longitude);

                                pd.dismiss();

                                Address locationAddress;

                                locationAddress = getAddress(latitude, longitude);
                                if (locationAddress != null) {

                                    String city = locationAddress.getLocality();

                                    String country = locationAddress.getCountryName();

                                    SharedPreferences.Editor editor = sharedPreferences.edit();
                                    editor.putString(PreferenceClass.CURRENT_LOCATION_LAT_LONG, latitude + "," + longitude);
                                    editor.putString(PreferenceClass.CURRENT_LOCATION_ADDRESS, city + " " + country);
                                    editor.putString(PreferenceClass.LATITUDE,String.valueOf(latitude));
                                    editor.putString(PreferenceClass.LONGITUDE,String.valueOf(longitude));

                                    editor.commit();

                                    welcome_location_txt.setText(getCurrentLocationAddress);
                                    welcome_location_txt.setText(city + " " + country);

                                }
                            }

                            }
                            catch (Exception e){
                                e.getMessage();
                            }
                        }

                    }

                });
    }

    @Override
    protected void onResume() {
        super.onResume();
        getCurrentLocation();
    }
*/



    @SuppressWarnings("deprecation")
    private void displayLocation() {
        getLocationPermission();

        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            // TODO: Consider calling
            //    ActivityCompat#requestPermissions
            // here to request the missing permissions, and then overriding
            //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
            //                                          int[] grantResults)
            // to handle the case where the user grants the permission. See the documentation
            // for ActivityCompat#requestPermissions for more details.
            return;
        }

        mLastLocation = LocationServices.FusedLocationApi.getLastLocation(mGoogleApiClient);

        if (mLastLocation != null) {
           latitude = mLastLocation.getLatitude();
           longitude = mLastLocation.getLongitude();

            Address locationAddress;

            locationAddress=getAddress(latitude,longitude);
            if(locationAddress!=null)
            {

                String city = locationAddress.getLocality();

                String country = locationAddress.getCountryName();

                if(!getCurrentLocationAddress.isEmpty()){
                    welcome_location_txt.setText(getCurrentLocationAddress);
                }
                else {

                    SharedPreferences.Editor editor = sharedPreferences.edit();
                    editor.putString(PreferenceClass.CURRENT_LOCATION_LAT_LONG, latitude + "," + longitude);
                    editor.putString(PreferenceClass.CURRENT_LOCATION_ADDRESS, city + " " + country);
                    editor.putString(PreferenceClass.LATITUDE, String.valueOf(latitude));
                    editor.putString(PreferenceClass.LONGITUDE, String.valueOf(longitude));
                    editor.commit();

                    welcome_location_txt.setText(getCurrentLocationAddress);
                    welcome_location_txt.setText(city + " " + country);
                }

            }

        } else {

            welcome_location_txt
                    .setText("Kalma Chowk, Lahore");
        }
    }

    protected synchronized void buildGoogleApiClient() {
        mGoogleApiClient = new GoogleApiClient.Builder(this)
                .addConnectionCallbacks(this)
                .addOnConnectionFailedListener(this)
                .addApi(LocationServices.API).build();
    }


    private boolean checkPlayServices() {
        int resultCode = GooglePlayServicesUtil
                .isGooglePlayServicesAvailable(this);
        if (resultCode != ConnectionResult.SUCCESS) {
            if (GooglePlayServicesUtil.isUserRecoverableError(resultCode)) {
                GooglePlayServicesUtil.getErrorDialog(resultCode, this,
                        PLAY_SERVICES_RESOLUTION_REQUEST).show();
            } else {
                Toast.makeText(getApplicationContext(),
                        "This device is not supported.", Toast.LENGTH_LONG)
                        .show();
                finish();
            }
            return false;
        }
        return true;
    }


    @Override
    protected void onStart() {
        super.onStart();
        if (mGoogleApiClient != null) {
            mGoogleApiClient.connect();
        }
    }

    @Override
    protected void onResume() {
        super.onResume();

        checkPlayServices();
    }


    @Override
    public void onConnectionFailed(@NonNull ConnectionResult connectionResult) {

    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == PERMISSION_DATA_ACCESS_CODE) {
            if(resultCode == RESULT_OK) {
                latitude = Double.parseDouble(data.getStringExtra("lat"));
                longitude = Double.parseDouble(data.getStringExtra("lng"));


                //  Geocoder gcd = new Geocoder(this, Locale.getDefault());
                Address locationAddress;

                locationAddress=getAddress(latitude,longitude);
                if(locationAddress!=null)
                {

                    String city = locationAddress.getLocality();

                    String country = locationAddress.getCountryName();
                    SharedPreferences.Editor editor = sharedPreferences.edit();
                    editor.putString(PreferenceClass.CURRENT_LOCATION_LAT_LONG,latitude+","+longitude);
                    editor.putString(PreferenceClass.CURRENT_LOCATION_ADDRESS,city+" " +country);
                    editor.putString(PreferenceClass.LATITUDE,String.valueOf(latitude));
                    editor.putString(PreferenceClass.LONGITUDE,String.valueOf(longitude));
                    editor.commit();

                    welcome_location_txt.setText(getCurrentLocationAddress);
                    welcome_location_txt.setText(city+" " +country);

                }


            }


        }

       /* if (requestCode == PLACE_PICKER_REQUEST) {
            if (resultCode == RESULT_OK) {
                Place place = PlacePicker.getPlace(data, getApplicationContext());
                StringBuilder stBuilder = new StringBuilder();
                String placename = String.format("%s", place.getName());
                latitude = place.getLatLng().latitude;
                longitude = place.getLatLng().longitude;
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

              //  Geocoder gcd = new Geocoder(this, Locale.getDefault());
                Address locationAddress;

                locationAddress=getAddress(latitude,longitude);
                    if(locationAddress!=null)
                    {

                        String city = locationAddress.getLocality();

                        String country = locationAddress.getCountryName();
                        SharedPreferences.Editor editor = sharedPreferences.edit();
                        editor.putString(PreferenceClass.CURRENT_LOCATION_LAT_LONG,latitude+","+longitude);
                        editor.putString(PreferenceClass.CURRENT_LOCATION_ADDRESS,city+" " +country);
                        editor.putString(PreferenceClass.LATITUDE,String.valueOf(latitude));
                        editor.putString(PreferenceClass.LONGITUDE,String.valueOf(longitude));
                        editor.commit();

                        welcome_location_txt.setText(getCurrentLocationAddress);
                        welcome_location_txt.setText(city+" " +country);

                    }


                }

            }*/

        }

    public Address getAddress(double latitude,double longitude)
    {
        Geocoder geocoder;
        List<Address> addresses;
        geocoder = new Geocoder(this, Locale.getDefault());

        try {
            addresses = geocoder.getFromLocation(latitude,longitude, 1); // Here 1 represent max location result to returned, by documents it recommended 1 to 5
            return addresses.get(0);

        } catch (IOException e) {
            e.printStackTrace();
        }

        return null;

    }

    private void buildAlertMessageNoGps() {
        builder = new AlertDialog.Builder(this);
        builder.setMessage("Your GPS seems to be disabled, do you want to enable it?")
                .setCancelable(false)
                .setPositiveButton("Yes", new DialogInterface.OnClickListener() {
                    public void onClick(@SuppressWarnings("unused") final DialogInterface dialog, @SuppressWarnings("unused") final int id) {
                        startActivity(new Intent(android.provider.Settings.ACTION_LOCATION_SOURCE_SETTINGS));
                    }
                })
                .setNegativeButton("No", new DialogInterface.OnClickListener() {
                    public void onClick(final DialogInterface dialog, @SuppressWarnings("unused") final int id) {
                        dialog.cancel();
                        //pd.dismiss();
                    }
                });
        final AlertDialog alert = builder.create();
        if(!getCurrentLocationAddress.isEmpty()){
            alert.cancel();
        }
        else {
            alert.show();
        }
    }

    @Override
    public void onConnected(@Nullable Bundle bundle) {
        displayLocation();
    }

    @Override
    public void onConnectionSuspended(int i) {
        mGoogleApiClient.connect();
    }


    private void getLocationPermission() {
    /*
     * Request location permission, so that we can get the location of the
     * device. The result of the permission request is handled by a callback,
     * onRequestPermissionsResult.
     */
        if (ContextCompat.checkSelfPermission(this.getApplicationContext(),
                android.Manifest.permission.ACCESS_FINE_LOCATION)
                == PackageManager.PERMISSION_GRANTED) {
            mLocationPermissionGranted = true;
        } else {
            try {
                ActivityCompat.requestPermissions(SplashScreen.this,
                        new String[]{android.Manifest.permission.ACCESS_FINE_LOCATION},
                        PERMISSIONS_REQUEST_ACCESS_FINE_LOCATION);
            }
            catch (Exception e){
                e.getMessage();
            }

        }
    }


    @Override
    public void onRequestPermissionsResult(int requestCode,
                                           @NonNull String permissions[],
                                           @NonNull int[] grantResults) {
        mLocationPermissionGranted = false;
        switch (requestCode) {
            case PERMISSIONS_REQUEST_ACCESS_FINE_LOCATION: {
                // If request is cancelled, the result arrays are empty.
                if (grantResults.length > 0
                        && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    mLocationPermissionGranted = true;
                    displayLocation();
                }
            }
        }

    }



    public void printKeyHash()  {
        try {
            PackageInfo info = getPackageManager().getPackageInfo(getPackageName() , PackageManager.GET_SIGNATURES);
            for(Signature signature:info.signatures)
            {
                MessageDigest md = MessageDigest.getInstance("SHA");
                md.update(signature.toByteArray());
                Log.i("keyhash" , Base64.encodeToString(md.digest(), Base64.DEFAULT));
            }
        } catch (PackageManager.NameNotFoundException e) {
            e.printStackTrace();
        } catch (NoSuchAlgorithmException e) {
            e.printStackTrace();
        }
    }

}
