package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.app.ProgressDialog;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;

public class PrivacyPolicyFragment extends Fragment {


    WebView mWebview;
    ImageView close_icon;
    TextView rider_jobs;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.rider_weekly_earning_fragment, container, false);
        // profile_pref = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);
        FrameLayout frameLayout = v.findViewById(R.id.weekly_earning_main_container);
        FontHelper.applyFont(getContext(),frameLayout, AllConstants.verdana);

        init(v);

        return v;
    }

    public void init(View v){

        rider_jobs = v.findViewById(R.id.rider_jobs);
        rider_jobs.setText(R.string.privacy_policy);

         callWebView(v);

        close_icon= v.findViewById(R.id.close_btn);
        close_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                Fragment restaurantMenuItemsFragment = new UserAccountFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.weekly_earning_main_container, restaurantMenuItemsFragment,"parent").commit();
            }
        });

    }


    public void callWebView(View v){
        final ProgressDialog pd = ProgressDialog.show(getContext(), "", "Please wait...", true);
        mWebview = v.findViewById(R.id.web_view);
        mWebview.getSettings().setJavaScriptEnabled(true);

        mWebview.getSettings().setLoadWithOverviewMode(true);
        mWebview.getSettings().setUseWideViewPort(true);
        mWebview.getSettings().setBuiltInZoomControls(true);


        mWebview.setWebViewClient(new WebViewClient() {
            public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
                Toast.makeText(getContext(), description, Toast.LENGTH_SHORT).show();
            }

            @Override
            public void onPageStarted(WebView view, String url, Bitmap favicon)
            {
                pd.show();
            }


            @Override
            public void onPageFinished(WebView view, String url) {
                pd.dismiss();

                String webUrl = mWebview.getUrl();

            }



    });
        mWebview.loadUrl("https://dinosoftlabs.com/foodies/privacy.php?device=app");}
}
