package com.dinosoftlabs.foodies.android.Adapters;

import android.content.Context;
import android.graphics.Bitmap;
import android.support.v4.util.LruCache;

import com.android.volley.Cache;
import com.android.volley.Network;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.BasicNetwork;
import com.android.volley.toolbox.DiskBasedCache;
import com.android.volley.toolbox.HurlStack;
import com.android.volley.toolbox.ImageLoader;

public class ServerImageParseAdapter {

    public static ServerImageParseAdapter SIAdapter;

    public static Context context1;

    public RequestQueue requestQueue1;

    public ImageLoader Imageloader1;

    public Cache cache1 ;

    public Network networkOBJ ;

    LruCache<String, Bitmap> LRUCACHE = new LruCache<String, Bitmap>(30);

    private ServerImageParseAdapter(Context context) {

        this.context1 = context;

        this.requestQueue1 = RQ();

        Imageloader1 = new ImageLoader(requestQueue1, new ImageLoader.ImageCache() {

            @Override
            public Bitmap getBitmap(String URL) {

                return LRUCACHE.get(URL);
            }

            @Override
            public void putBitmap(String url, Bitmap bitmap) {

                LRUCACHE.put(url, bitmap);
            }
        });
    }

    public ImageLoader getImageLoader() {

        return Imageloader1;
    }

    public static ServerImageParseAdapter getInstance(Context SynchronizedContext) {

        if (SIAdapter == null) {

            SIAdapter = new ServerImageParseAdapter(SynchronizedContext);
        }
        return SIAdapter;
    }

    public RequestQueue RQ() {

        if (requestQueue1 == null) {

            cache1 = new DiskBasedCache(context1.getCacheDir());

            networkOBJ = new BasicNetwork(new HurlStack());

            requestQueue1 = new RequestQueue(cache1, networkOBJ);

            requestQueue1.start();
        }
        return requestQueue1;
    }
}
