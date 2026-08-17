import type { UseFetchOptions } from "#app";

export function useApi<T = unknown>(
  request: string | Ref<string>,
  options?: UseFetchOptions<T>
) {
  const toast = useToast();
  const session = useSession();
  return useFetch(request, {
    onRequest({ options: reqOptions }) {
      const token = session.token || session.tokenCookie;
      if (token) {
        const headers = new Headers(reqOptions.headers || {});
        headers.set("Authorization", `Bearer ${token}`);
        reqOptions.headers = headers;
      }
    },
    onResponseError({ response }) {
      const meta = response?._data?.meta;
      let msg = "";

      if (Array.isArray(meta?.message) && meta.message.length > 0) {
        msg = meta.message[0];
      } else if (typeof meta?.message === "string" && meta.message) {
        msg = meta.message;
      } else if (Array.isArray(meta?.messages) && meta.messages.length > 0) {
        msg = meta.messages[0];
      } else if (typeof meta?.validations === "string" && meta.validations) {
        msg = meta.validations;
      } else if (meta?.validations && typeof meta.validations === "object") {
        const firstKey = Object.keys(meta.validations)[0];
        if (firstKey) {
          const val = meta.validations[firstKey];
          msg = Array.isArray(val) ? val[0] : String(val);
        }
      } else if (response?._data?.message) {
        msg = response._data.message;
      }

      if (msg) {
        toast.add({
          color: "red",
          title: msg,
        });
      }
    },
    retry: false,
    ...options,
  });
}

